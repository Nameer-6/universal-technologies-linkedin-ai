import React, { useEffect, useState, useMemo } from 'react';
import { Card } from '@/components/ui/card';
import { ThumbsUp, MessageCircle, Repeat, Eye, ListTodo } from 'lucide-react';
import { Toaster, toast } from 'react-hot-toast';
import Header from '@/components/Header';

const LinkedInPostMetrics = () => {
  const [metrics, setMetrics] = useState({});
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch('/api/metrics', { credentials: 'include' })
      .then(async res => {
        if (!res.ok) {
          const err = await res.json();
          throw new Error(err.error || 'Failed to fetch metrics');
        }
        return res.json();
      })
      .then(data => {
        setMetrics(data.metrics || {});
        setLoading(false);
      })
      .catch(err => {
        setError(err.message);
        setLoading(false);
      });
  }, []);

  const summary = useMemo(() => {
    const items = Object.values(metrics);
    return {
      totalPosts: items.length,
      totalLikes: items.reduce((sum, { likes }) => sum + likes, 0),
      totalComments: items.reduce((sum, { comments }) => sum + comments, 0),
      totalShares: items.reduce((sum, { reposts }) => sum + reposts, 0),
      totalImpressions: items.reduce((sum, { impressions }) => sum + impressions, 0),
    };
  }, [metrics]);

  if (loading) {
    return (
      <div className="flex items-center justify-center h-64">
        <span className="animate-pulse text-gray-500">Loading metrics…</span>
      </div>
    );
  }
  if (error) {
    toast.error(error);
    return <div className="text-red-500 text-center py-4">Error: {error}</div>;
  }

  const summaryItems = [
    { label: 'Total Posts', value: summary.totalPosts, icon: <ListTodo className="w-6 h-6 text-gray-700" /> },
    { label: 'Likes', value: summary.totalLikes, icon: <ThumbsUp className="w-6 h-6 text-blue-500" /> },
    { label: 'Comments', value: summary.totalComments, icon: <MessageCircle className="w-6 h-6 text-green-500" /> },
    { label: 'Shares', value: summary.totalShares, icon: <Repeat className="w-6 h-6 text-purple-500" /> },
    { label: 'Impressions', value: summary.totalImpressions, icon: <Eye className="w-6 h-6 text-indigo-500" /> },
  ];

  return (
    <div className="relative max-w-7xl mx-auto px-6 py-10">
      <Toaster />

      {/* Header */}
      <div className="mb-10">
        <Header />
      </div>

      {/* Top-left Gradient Shade */}
      <div
        className="absolute top-10 left-10 w-96 h-96
                   bg-gradient-to-r from-blue-200 to-indigo-300
                   rounded-full mix-blend-multiply filter blur-3xl opacity-40
                   pointer-events-none animate-pulse-slow"
      />

      {/* Bottom-right Gradient Shade */}
      <div
        className="absolute bottom-10 right-10 w-64 h-64
                   bg-gradient-to-r from-purple-200 to-pink-300
                   rounded-full mix-blend-multiply filter blur-3xl opacity-40
                   pointer-events-none animate-pulse-slow"
      />

      <div className="relative z-10 space-y-8">
        <h2 className="text-4xl font-extrabold text-gray-800">
          LinkedIn Post Analytics
        </h2>

        {/* Summary KPIs */}
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
          {summaryItems.map(({ label, value, icon }) => (
            <Card
              key={label}
              className="flex items-center gap-4 p-4 bg-white shadow-md hover:shadow-lg transition"
            >
              <div className="p-2 bg-gray-100 rounded-full">{icon}</div>
              <div>
                <p className="text-2xl font-semibold text-gray-900">{value}</p>
                <p className="text-sm text-gray-500">{label}</p>
              </div>
            </Card>
          ))}
        </div>

        {/* Detail Cards */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {Object.entries(metrics).map(([urn, data]) => (
            <Card
              key={urn}
              className="border-2 border-transparent hover:border-linkedin-primary
                         p-6 bg-white shadow-sm hover:shadow-md transition">
              <p className="text-xs text-gray-400 mb-2 truncate">URN: {urn}</p>
              <p className="font-medium text-gray-800 mb-4 line-clamp-3">
                {data.post_text}
              </p>

              <div className="grid grid-cols-2 gap-4">
                <div className="flex items-center gap-2 text-sm">
                  <ThumbsUp className="w-5 h-5 text-blue-500" />
                  <span>{data.likes}</span>
                </div>
                <div className="flex items-center gap-2 text-sm">
                  <MessageCircle className="w-5 h-5 text-green-500" />
                  <span>{data.comments}</span>
                </div>
                <div className="flex items-center gap-2 text-sm">
                  <Repeat className="w-5 h-5 text-purple-500" />
                  <span>{data.reposts}</span>
                </div>
                <div className="flex items-center gap-2 text-sm">
                  <Eye className="w-5 h-5 text-indigo-500" />
                  <span>{data.impressions}</span>
                </div>
              </div>
            </Card>
          ))}
        </div>
      </div>
    </div>
  );
};

export default LinkedInPostMetrics;