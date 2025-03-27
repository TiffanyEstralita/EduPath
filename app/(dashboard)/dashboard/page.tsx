// displays the home page for the user
'use client';

import Link from 'next/link';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import {
  Sparkles,
  TrendingUp,
  LineChart,
  Bookmark,
  Settings2,
} from 'lucide-react';

export default function DashboardPage() {
  return (
    <div className="p-6 space-y-8">
      {/* Welcome Section */}
      <div className="text-center">
        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
          Welcome to EduPath 🎓
        </h1>
        <p className="text-gray-600 dark:text-gray-300 mt-2 text-lg">
          Your personalized career and education hub.
        </p>
      </div>

      {/* Quick Links Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <DashboardCard
          href="/dashboard/recommendations"
          icon={Sparkles}
          title="Recommendations"
          description="Get personalized job & course suggestions."
        />
        <DashboardCard
          href="/dashboard/stats"
          icon={TrendingUp}
          title="Employment Stats"
          description="View market trends and job demand."
        />
        <DashboardCard
          href="/dashboard/visualization"
          icon={LineChart}
          title="Trends & Graphs"
          description="Analyze career pathways with data insights."
        />
        <DashboardCard
          href="/dashboard/saved-pathways"
          icon={Bookmark}
          title="Saved Pathways"
          description="Access your bookmarked career paths."
        />
        <DashboardCard
          href="/dashboard/settings"
          icon={Settings2}
          title="Settings"
          description="Manage your account and preferences."
        />
      </div>
    </div>
  );
}

// Reusable Dashboard Card Component
function DashboardCard({
  href,
  icon: Icon,
  title,
  description,
}: {
  href: string;
  icon: any;
  title: string;
  description: string;
}) {
  return (
    <Link href={href} passHref>
      <Card className="hover:shadow-xl transition-transform transform hover:scale-105 duration-200 cursor-pointer bg-white dark:bg-gray-800 border dark:border-gray-700">
        <CardHeader className="flex flex-row items-center gap-4 p-5">
          <Icon className="w-12 h-12 text-blue-600 dark:text-blue-400" />
          <CardTitle className="text-xl font-semibold text-gray-900 dark:text-white">
            {title}
          </CardTitle>
        </CardHeader>
        <CardContent className="p-5">
          <p className="text-gray-600 dark:text-gray-300 text-sm">{description}</p>
        </CardContent>
      </Card>
    </Link>
  );
}
