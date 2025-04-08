import { NextResponse } from "next/server";
import { db } from "@/lib/db";
import programs from "@/public/programs.json"; // Adjust if this is a .ts or .json file

export async function POST(req: Request) {
	try {
		const body = await req.json();
		const firebase_uid = body.firebase_uid;

		if (!firebase_uid) {
			return NextResponse.json({ error: "Firebase UID is required" }, { status: 400 });
		}

		// Fetch user profile from MySQL
		const [rows]: any = await db.execute(
			"SELECT interests FROM user_profiles WHERE firebase_uid = ?",
			[firebase_uid]
		);

		if (!rows || rows.length === 0) {
			return NextResponse.json({ error: "Profile not found" }, { status: 404 });
		}

		const userInterest = rows[0].interests;

		// If interests is comma-separated (like "Design, Tech"), split it
		const interestList = userInterest.split(',').map((i: string) => i.trim().toLowerCase());

		// Filter programs based on any of the user's interests
		const recommendedCourses = programs.filter((course: any) =>
			interestList.some((interest: string) =>
				course.course_name?.toLowerCase().includes(interest) ||
				course.category?.toLowerCase().includes(interest)
			)
		);


		return NextResponse.json({ recommendations: recommendedCourses });
	} catch (error) {
		console.error("Recommendation error:", error);
		return NextResponse.json({ error: "Internal Server Error" }, { status: 500 });
	}
}
