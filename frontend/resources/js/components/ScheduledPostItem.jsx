import React from "react";
import { Button } from "@/components/ui/button";
import { MdDelete } from "react-icons/md";
import { CiEdit } from "react-icons/ci";
import { Clock3, Clock9, FileCheck } from "lucide-react";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";

// Add plugins to dayjs
dayjs.extend(utc);
dayjs.extend(timezone);

function truncateText(htmlText, wordLimit = 15) {
  if (!htmlText) return "";
  const text = htmlText.replace(/<[^>]*>?/gm, "");
  const words = text.split(/\s+/);
  if (words.length <= wordLimit) return text;
  return words.slice(0, wordLimit).join(" ") + "...";
}

const ScheduledPostItem = ({ post, onCancel, onEdit, onDeletePublishedPost }) => {
  // Format scheduled datetime using dayjs with timezone
  let scheduledDisplay = "";
  if (post.scheduled_datetime && post.timezone) {
    // This assumes scheduled_datetime is stored in UTC (or treated as UTC)
  scheduledDisplay = dayjs.utc(post.scheduled_datetime)
    .tz(dayjs.tz.guess())
    .format("MMM D, YYYY h:mm A");

  } else if (post.scheduled_datetime) {
    scheduledDisplay = dayjs(post.scheduled_datetime).format("MMM D, YYYY h:mm A");
  } else {
    scheduledDisplay = `${post.scheduled_date || post.scheduledDate || ""} · ${post.scheduled_time || post.scheduledTime || ""}`;
  }

  const displayContent =
    post.status === "pending" || post.status === "published"
      ? truncateText(post.post_text || post.content, 15)
      : post.post_text || post.content;

  return (
    <div className="group p-4 border rounded-lg mb-3 hover:border-linkedin-primary transition-all bg-white shadow-sm hover:shadow-md">
      <div className="flex justify-between items-start">
        <div className="flex-1">
          <div className="flex items-center gap-2 mb-2">
            <span className="bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded-full flex items-center gap-1">
              <Clock3 size={12} />
              <span>{scheduledDisplay}</span>
            </span>
            {post.status === "pending" && (
              <span className="bg-amber-100 text-amber-700 text-xs px-2 py-1 rounded-full flex items-center gap-1">
                <Clock9 size={12} />
                <span>Pending</span>
              </span>
            )}
            {post.status === "published" && (
              <span className="bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full flex items-center gap-1">
                <FileCheck size={12} />
                <span>Published</span>
              </span>
            )}
          </div>
          <p className="text-sm text-gray-800 mb-2">{displayContent}</p>
        </div>
        <div className="flex ml-4">
          {post.status === "pending" && (
            <>
              <Button
                variant="ghost"
                size="sm"
                className="p-2 h-8 w-8"
                onClick={() => onEdit(post)}
              >
                <CiEdit size={20} className="text-linkedin-primary" />
              </Button>
              <Button
                variant="ghost"
                size="sm"
                className="p-2 h-8 w-8"
                onClick={() => onCancel(post.id)}
              >
                <MdDelete size={20} className="text-red-500" />
              </Button>
            </>
          )}
          {post.status === "published" && (
            <>
              <Button
                variant="ghost"
                size="sm"
                className="p-2 h-8 w-8"
                onClick={() => onCancel(post.id)}
              >
                <MdDelete size={20} className="text-red-500" />
              </Button>
            </>
          )}
        </div>
      </div>
    </div>
  );
};

export default ScheduledPostItem;
