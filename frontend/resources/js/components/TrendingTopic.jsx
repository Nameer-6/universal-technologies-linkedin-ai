import React from "react";
import {
    ExternalLink,
} from "lucide-react";

const TrendingTopic = ({ title, source, url, onCreatePost }) => (
  <div
    className="group p-4 border-b last:border-b-0 hover:bg-gray-50 transition-colors cursor-pointer"
    onClick={onCreatePost}
  >
    <div className="flex justify-between items-start">
      <div>
        <h3 className="font-semibold text-gray-800 group-hover:text-gray-900 transition-colors">
          {title}
        </h3>
        <p className="text-sm text-gray-500">Source: {source}</p>
      </div>
      <div className="flex gap-2">
        <a
          href={url}
          target="_blank"
          rel="noopener noreferrer"
          title="Open original article"
          onClick={(e) => e.stopPropagation()}
          className="text-gray-400 hover:text-gray-600 transition-colors"
        >
          <ExternalLink size={18} />
        </a>
      </div>
    </div>
  </div>
);

export default TrendingTopic