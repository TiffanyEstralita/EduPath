'use server';

import { z } from 'zod';
import { eq } from 'drizzle-orm';
import { db } from '@/lib/db/drizzle';
import { users, type NewUser } from '@/lib/db/schema';
import { comparePasswords, hashPassword, setSession } from '@/lib/auth/session';
import { redirect } from 'next/navigation';
import { cookies } from 'next/headers';
import { validatedAction, validatedActionWithUser } from '@/lib/auth/middleware';

const signInSchema = z.object({
  email: z.string().email().min(3).max(255),
  password: z.string().min(8).max(100),
});

export const signIn = validatedAction(signInSchema, async (data) => {
  const { email, password } = data;

  const existingUser = await db
    .select()
    .from(users)
    .where(eq(users.email, email))
    .limit(1);

  if (existingUser.length === 0) {
    return { error: 'Invalid email or password. Please try again.' };
  }

  const foundUser = existingUser[0];
  const isPasswordValid = await comparePasswords(password, foundUser.passwordHash);

  if (!isPasswordValid) {
    return { error: 'Invalid email or password. Please try again.' };
  }

  await setSession(foundUser);
  redirect('/dashboard'); // Redirect to dashboard after signing in
});

const signUpSchema = z.object({
  email: z.string().email(),
  password: z.string().min(8),
});

export const signUp = validatedAction(signUpSchema, async (data) => {
  const { email, password } = data;

  const existingUser = await db
    .select()
    .from(users)
    .where(eq(users.email, email))
    .limit(1);

  if (existingUser.length > 0) {
    return { error: 'User already exists. Please sign in.' };
  }

  const passwordHash = await hashPassword(password);

  const newUser: NewUser = {
    email,
    passwordHash,
  };

  const [createdUser] = await db.insert(users).values(newUser).returning();

  if (!createdUser) {
    return { error: 'Failed to create user. Please try again.' };
  }

  await setSession(createdUser);
  redirect('/profile'); // Redirect to profile setup after signup
});

export async function signOut() {
  (await cookies()).delete('session');
  redirect('/sign-in');
}

const updatePasswordSchema = z
  .object({
    currentPassword: z.string().min(8).max(100),
    newPassword: z.string().min(8).max(100),
    confirmPassword: z.string().min(8).max(100),
  })
  .refine((data) => data.newPassword === data.confirmPassword, {
    message: "Passwords don't match",
    path: ['confirmPassword'],
  });

export const updatePassword = validatedActionWithUser(updatePasswordSchema, async (data, _, user) => {
  const { currentPassword, newPassword } = data;

  const isPasswordValid = await comparePasswords(currentPassword, user.passwordHash);

  if (!isPasswordValid) {
    return { error: 'Current password is incorrect.' };
  }

  if (currentPassword === newPassword) {
    return { error: 'New password must be different from the current password.' };
  }

  const newPasswordHash = await hashPassword(newPassword);

  await db.update(users).set({ passwordHash: newPasswordHash }).where(eq(users.id, user.id));

  return { success: 'Password updated successfully.' };
});

const deleteAccountSchema = z.object({
  password: z.string().min(8).max(100),
});

export const deleteAccount = validatedActionWithUser(deleteAccountSchema, async (data, _, user) => {
  const { password } = data;

  const isPasswordValid = await comparePasswords(password, user.passwordHash);
  if (!isPasswordValid) {
    return { error: 'Incorrect password. Account deletion failed.' };
  }

  await db.delete(users).where(eq(users.id, user.id));

  (await cookies()).delete('session');
  redirect('/sign-in');
});

const updateAccountSchema = z.object({
  name: z.string().min(1, 'Name is required').max(100),
  email: z.string().email('Invalid email address'),
});

export const updateAccount = validatedActionWithUser(updateAccountSchema, async (data, _, user) => {
  const { name, email } = data;

  await db.update(users).set({ name, email }).where(eq(users.id, user.id));

  return { success: 'Account updated successfully.' };
});
