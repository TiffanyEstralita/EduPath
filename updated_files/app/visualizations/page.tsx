'use client';

import { useEffect, useState } from 'react';
import { Bar } from 'react-chartjs-2';
import { useRouter } from 'next/navigation';
import {
  Chart as ChartJS,
  BarElement,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend,
  Title,
} from 'chart.js';

ChartJS.register(
  BarElement,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend,
  Title
);

// Type definitions
type JobVacancy = {
  quarter: string;
  sector: string;
  sub_sector: string;
  occupation: string;
  job_vacancy_rate: number;
};

type GraduateEmployment = {
  year: string;
  university: string;
  degree: string;
  employment_rate: number;
  median_salary: number;
};

type IncomeData = {
  year: string;
  sex: string;
  occupation: string;
  median_income: number;
};

export default function CombinedVisualizationsPage() {
  const router = useRouter();

  const [vacancyData, setVacancyData] = useState<JobVacancy[]>([]);
  const [employmentData, setEmploymentData] = useState<GraduateEmployment[]>([]);
  const [incomeData, setIncomeData] = useState<IncomeData[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchAllData = async () => {
      try {
        const vacancyRes = await fetch('/job_vacancy.json');
        const vacancyJson = await vacancyRes.json();
        setVacancyData(vacancyJson);

        const employmentRes = await fetch('/graduate_employment.json');
        const employmentJson = await employmentRes.json();
        setEmploymentData(Object.values(employmentJson).slice(0, 10) as GraduateEmployment[]);

        const incomeRes = await fetch('/median_income_by_occupation.json');
        const incomeJson = await incomeRes.json();
        setIncomeData(incomeJson);
      } catch (err) {
        console.error("Error fetching data:", err);
      } finally {
        setLoading(false);
      }
    };

    fetchAllData();
  }, []);

  // Chart configurations
  const vacancyChart = {
    labels: vacancyData.map(item => item.occupation),
    datasets: [{
      label: 'Job Vacancy Rate (%)',
      data: vacancyData.map(item => item.job_vacancy_rate),
      backgroundColor: 'rgba(75, 192, 192, 0.6)',
      borderColor: 'rgba(75, 192, 192, 1)',
      borderWidth: 1,
    }],
  };

  const employmentChart = {
    labels: employmentData.map(item => item.degree),
    datasets: [{
      label: 'Employment Rate (%)',
      data: employmentData.map(item => item.employment_rate),
      backgroundColor: 'rgba(153, 102, 255, 0.6)',
      borderColor: 'rgba(153, 102, 255, 1)',
      borderWidth: 1,
    }],
  };

  const occupations = [...new Set(incomeData.map((item) => item.occupation))];
  const maleData = occupations.map((occupation) => incomeData.find((d) => d.occupation === occupation && d.sex === 'Male')?.median_income || 0);
  const femaleData = occupations.map((occupation) => incomeData.find((d) => d.occupation === occupation && d.sex === 'Female')?.median_income || 0);

  const incomeChart = {
    labels: occupations,
    datasets: [
      {
        label: 'Male Median Income',
        data: maleData,
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1,
      },
      {
        label: 'Female Median Income',
        data: femaleData,
        backgroundColor: 'rgba(255, 99, 132, 0.6)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1,
      },
    ],
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 to-white py-12 px-6">
      <div className="max-w-7xl mx-auto">
        <h1 className="text-4xl font-bold text-center text-gray-800 mb-12">
          Labour Market Visualisations
        </h1>

        {loading ? (
          <div className="text-center text-gray-500 text-lg">Loading charts...</div>
        ) : (
          <div className="grid gap-8 grid-cols-1 md:grid-cols-2">
            {/* Chart Card Component */}
            {[{
              title: "Job Vacancy Rate by Occupation",
              chartData: vacancyChart,
            }, {
              title: "Graduate Employment Rate by Degree",
              chartData: employmentChart,
            }, {
              title: "Median Monthly Income by Occupation and Gender (2023)",
              chartData: incomeChart,
            }].map((chart, i) => (
              <div
                key={i}
                className="bg-white shadow-lg rounded-xl p-6 transition hover:shadow-2xl"
              >
                <h2 className="text-xl font-semibold text-gray-700 mb-4 text-center">
                  {chart.title}
                </h2>
                <div className="overflow-x-auto">
                  <Bar data={chart.chartData} options={{ responsive: true }} />
                </div>
              </div>
            ))}
          </div>
        )}

        {/* Back to Dashboard Button */}
        <div className="flex justify-center mt-12">
          <button
            onClick={() => router.push('/dashboard')}
            className="bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition"
          >
            Back to Dashboard
          </button>
        </div>
      </div>
    </div>
  );
}
