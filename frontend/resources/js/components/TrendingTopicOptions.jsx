import React, { useState, useEffect, useRef } from 'react';
import { ChevronDown, Zap, RefreshCw } from 'lucide-react';
import { FaSearch } from 'react-icons/fa';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import TrendingTopic from './TrendingTopic';
import { toast } from 'react-hot-toast';

const TrendingTopicOptions = ({
  trendingTopics = [],
  isFetchingInitial,
  totalTrendingCount,
  fetchTrending,
  handleRefreshTrending,
  isRefreshing,
  handleCreateFromTopic,
  offset,
  setOffset,
  limit,
}) => {
  const [trendingField, setTrendingField] = useState('');
  const [trendingCountry, setTrendingCountry] = useState('');
  const [selectedInfluencer, setSelectedInfluencer] = useState('');
  const [searchQuery, setSearchQuery] = useState('');
  const [hasSearched, setHasSearched] = useState(false);
  const [isLoadingMore, setIsLoadingMore] = useState(false);
  const trendingContainerRef = useRef(null);

  // Auto-fetch when both category and country are selected
  useEffect(() => {
    if (trendingField && trendingCountry) {
      setHasSearched(false);
      setOffset(0);
      fetchTrending({
        offset: 0,
        category: trendingField,
        country: trendingCountry,
        influencer: selectedInfluencer || undefined,
      });
    }
  }, [
    trendingField,
    trendingCountry,
    selectedInfluencer,
    fetchTrending,
    setOffset,
  ]);

  // Handle search keyword
  const handleSearch = () => {
    if (!searchQuery.trim()) {
      toast.error('Please enter a keyword to search.');
      return;
    }
    setHasSearched(true);
    setTrendingField('');
    setTrendingCountry('');
    setOffset(0);
    fetchTrending({
      offset: 0,
      q: searchQuery,
      influencer: selectedInfluencer || undefined,
    });
  };

  // Pagination: load more
  useEffect(() => {
    if (offset > 0) {
      setIsLoadingMore(true);
      fetchTrending({
        offset,
        category: hasSearched ? undefined : trendingField,
        country: hasSearched ? undefined : trendingCountry,
        q: hasSearched ? searchQuery : undefined,
        influencer: selectedInfluencer || undefined,
      }).finally(() => setIsLoadingMore(false));
    }
  }, [
    offset,
    fetchTrending,
    trendingField,
    trendingCountry,
    searchQuery,
    hasSearched,
    selectedInfluencer,
  ]);

  return (
    <div>
      {/* Header */}
      <div className="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b flex items-center justify-between">
        <h2 className="text-xl font-semibold flex items-center gap-2 text-gray-800">
          <Zap className="text-[#f2cf19]" size={20} />
          Trending Topics
        </h2>
        <Button
          variant="ghost"
          size="sm"
          className="flex items-center gap-1 hover:text-linkedin-primary"
          onClick={() => {
            // Respect last action: search or filter
            handleRefreshTrending();
          }}
        >
          <RefreshCw size={16} className={isRefreshing ? 'animate-spin' : ''} />
          <span>Refresh</span>
        </Button>
      </div>

      {/* Filters & Search */}
      <div className="p-4">
        <div className="mb-6">
          {/* Influencer dropdown */}
          <div className="mb-4">
            <label className="text-sm text-gray-500 block mb-1">Inspiration</label>
            <div className="relative">
              <select
                className="block w-full bg-white border rounded-lg p-2 pr-10 focus:outline-linkedin-primary"
                value={selectedInfluencer}
                onChange={e => setSelectedInfluencer(e.target.value)}
              >
                <option value="">Random</option>
                <option>Oana Labes</option>
                <option>Robert Kiyosaki</option>
                <option>Farnoosh Torabi</option>
                <option>Josh Aharonoff</option>
                <option>Graham Stephan</option>
              </select>
              <ChevronDown className="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
            </div>
          </div>

          {/* Category & Country */}
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label className="text-sm text-gray-500 block mb-1">Category</label>
              <div className="relative">
                <select
                  className="block w-full bg-white border rounded-lg p-2 pr-10 focus:outline-linkedin-primary"
                  value={trendingField}
                  onChange={e => setTrendingField(e.target.value)}
                >
                  <option value="">Select Topic</option>
                  <option value="business">Business</option>
                  <option value="entertainment">Entertainment</option>
                  <option value="general">General</option>
                  <option value="health">Health</option>
                  <option value="science">Science</option>
                  <option value="sports">Sports</option>
                  <option value="technology">Technology</option>
                </select>
                <ChevronDown className="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
              </div>
            </div>

            <div>
              <label className="text-sm text-gray-500 block mb-1">Country</label>
              <div className="relative">
                <select
                  className="block w-full bg-white border rounded-lg p-2 pr-10 focus:outline-linkedin-primary"
                  value={trendingCountry}
                  onChange={e => setTrendingCountry(e.target.value)}
                >
                  <option value="">Select Country</option>
                  <option value="us">United States</option>
                  <option value="ca">Canada</option>
                  <option value="gb">United Kingdom</option>
                  <option value="au">Australia</option>
                  <option value="in">India</option>
                  <option value="pk">Pakistan</option>
                  {/* ...other countries */}
                </select>
                <ChevronDown className="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
              </div>
            </div>
          </div>

          {/* Search */}
          <div className="mt-6">
            <label className="text-sm text-gray-500 block mb-1">Search</label>
            <div className="flex items-center gap-2">
              <Input
                value={searchQuery}
                onChange={e => setSearchQuery(e.target.value)}
                placeholder="Type to search…"
                className="flex-1 p-2 border rounded focus:outline-none focus:ring-linkedin-primary"
              />
              <Button onClick={handleSearch} className="bg-linkedin-primary rounded-full text-white">
                <FaSearch />
              </Button>
            </div>
          </div>
        </div>

        {/* Placeholder when no filters/search */}
        {(!trendingField || !trendingCountry) && !hasSearched ? (
          <div className="text-center text-gray-500 py-4">
            Please select a category & country, or enter a keyword to search.
          </div>
        ) : isFetchingInitial ? (
          <p className="text-center py-4">Loading trending topics...</p>
        ) : (
          <div ref={trendingContainerRef} className="border rounded-md bg-white divide-y">
            {trendingTopics.map((topic, idx) => (
              <TrendingTopic
                key={idx}
                title={topic.title}
                source={topic.source}
                url={topic.url}
                onCreatePost={() => handleCreateFromTopic(topic.title, topic.description)}
              />
            ))}
            {isLoadingMore && <div className="p-4 text-center animate-blink">Loading more...</div>}
          </div>
        )}

        {/* Load More */}
        {!isFetchingInitial && !isLoadingMore && offset + limit < totalTrendingCount && (
          <div className="mt-4 text-center">
            <Button onClick={() => setOffset(offset + limit)} className="bg-linkedin-primary font-bold">
              Load More
            </Button>
          </div>
        )}
      </div>
    </div>
  );
};

export default TrendingTopicOptions;
