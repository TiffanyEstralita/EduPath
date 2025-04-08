"use client";
import { useRouter } from "next/navigation";
import Image from "next/image";

export default function HomePage() {
  const router = useRouter();

  return (
    <main className="min-h-screen bg-white flex flex-col items-center justify-between p-6 sm:p-12">
      {/* Hero Section */}
      <section className="max-w-6xl w-full flex flex-col-reverse md:flex-row items-center justify-between gap-8">
        {/* Text Content */}
        <div className="flex-1 text-center md:text-left">
          <h1 className="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-4 leading-tight">
            Discover Your <span className="text-blue-600">Future</span> with EduPath
          </h1>
          <p className="text-lg sm:text-xl text-gray-600 mb-6">
            Personalized course and job recommendations based on your interests and skills.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
            <button
              onClick={() => router.push("/login")}
              className="bg-blue-600 text-white px-6 py-3 rounded-xl shadow hover:bg-blue-700 transition"
            >
              Log In
            </button>
            <button
              onClick={() => router.push("/signup")}
              className="bg-gray-100 text-gray-800 px-6 py-3 rounded-xl hover:bg-gray-200 transition"
            >
              Sign Up
            </button>
          </div>
        </div>

        {/* Hero Image */}
        <div className="flex-1">
          <Image
            src="/hero-image.svg" // Make sure this file exists in `public/`
            alt="EduPath Hero Illustration"
            width={600}
            height={400}
            className="w-full h-auto"
            priority
          />
        </div>
      </section>

      {/* Footer */}
      <footer className="mt-12 text-sm text-gray-400 text-center">
        © {new Date().getFullYear()} EduPath. All rights reserved.
      </footer>
    </main>
  );
}
