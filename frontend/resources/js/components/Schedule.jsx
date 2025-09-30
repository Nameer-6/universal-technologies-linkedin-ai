import axios from "axios";
import dayjs from "dayjs";
import timezone from "dayjs/plugin/timezone";
import utc from "dayjs/plugin/utc";
import React, { useEffect, useRef, useState } from "react";
import { toast } from "react-hot-toast";
import { MdClose, MdOutlineDelete, MdOutlineEdit } from "react-icons/md";
import { useNavigate } from "react-router-dom";
import profile from "../assets/img/user-default.jpg";

// Extend dayjs with the plugins
dayjs.extend(utc);
dayjs.extend(timezone);

export default function Schedule({ scheduledPosts = [], refreshPosts }) {
  const navigate = useNavigate();
  const [activeTab, setActiveTab] = useState("queue");
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  // Dynamic profile info from API.
  const [profileInfo, setProfileInfo] = useState({
    name: "",
    email: "",
    profilePic: ""
  });

  // Refs for dropdown menu
  const dropdownRef = useRef(null);
  useEffect(() => {
    function handleClickOutside(e) {
      if (
        dropdownOpen &&
        dropdownRef.current &&
        !dropdownRef.current.contains(e.target)
      ) {
        setDropdownOpen(false);
      }
    }
    document.addEventListener("mousedown", handleClickOutside);
    return () =>
      document.removeEventListener("mousedown", handleClickOutside);
  }, [dropdownOpen]);

  // Fetch dynamic profile info
  useEffect(() => {
    const token = localStorage.getItem('auth_token');
    if (!token) {
      setProfileInfo({
        name: "User Name",
        email: "user@example.com",
        profilePic: profile,
      });
      return;
    }
    
    axios
      .get("/api/profile", { 
        withCredentials: true,
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      })
      .then((response) => {
        setProfileInfo({
          name: response.data.name || "User Name",
          email: response.data.email || "user@example.com",
          profilePic: response.data.profilePic || profile,
        });
      })
      .catch((err) => {
        console.error("Failed to load profile info:", err);
      });
  }, []);

  const handleLogout = (e) => {
    e.preventDefault();
    localStorage.clear();
    toast.success("Logged out successfully!");
    navigate("/");
  };

  const deleteScheduledPost = async (postId) => {
    if (!window.confirm("Are you sure you want to delete this scheduled post?"))
      return;
    try {
      const res = await axios.post(
        "/api/scheduled-posts/delete",
        { id: postId },
        { withCredentials: true }
      );
      if (res.data.message) {
        toast.success(res.data.message);
        // Refresh scheduled posts in realtime.
        if (typeof refreshPosts === "function") {
          refreshPosts();
        }
      } else {
        toast.error(res.data.error || "Error deleting scheduled post");
      }
    } catch (err) {
      toast.error("Error deleting scheduled post");
    }
  };

  const getFirstSentence = (text) => {
    if (!text) return "";
    const sentences = text.match(/[^.!?]+[.!?]+/g) || [text];
    return sentences[0].trim();
  };

  // --- Pagination state for each tab ---
  const [currentPageQueue, setCurrentPageQueue] = useState(1);
  const [currentPagePosted, setCurrentPagePosted] = useState(1);
  const postsPerPage = 5;

  // Helper function to render pagination controls.
  const renderPagination = (currentPage, totalPages, onPageChange) => {
    let pages = [];
    if (totalPages <= 5) {
      for (let i = 1; i <= totalPages; i++) {
        pages.push(i);
      }
    } else {
      if (currentPage <= 3) {
        pages = [1, 2, 3, "...", totalPages];
      } else if (currentPage >= totalPages - 2) {
        pages = [1, "...", totalPages - 2, totalPages - 1, totalPages];
      } else {
        pages = [1, "...", currentPage, "...", totalPages];
      }
    }
    return (
      <div className="flex space-x-2 mt-4 justify-center">
        {currentPage > 1 && (
          <button
            onClick={() => onPageChange(currentPage - 1)}
            className="px-2 py-1 border rounded"
          >
            Previous
          </button>
        )}
        {pages.map((page, index) =>
          page === "..." ? (
            <span key={index} className="px-2 py-1">
              ...
            </span>
          ) : (
            <button
              key={index}
              onClick={() => onPageChange(page)}
              style={currentPage === page ? { backgroundColor: "#0014FF", color: "white" } : {}}
              className="px-3 py-2 border rounded"
            >
              {page}
            </button>
          )
        )}
        {currentPage < totalPages && (
          <button
            onClick={() => onPageChange(currentPage + 1)}
            className="px-2 py-2 border rounded"
          >
            Next
          </button>
        )}
      </div>
    );
  };

  const renderQueueTab = () => {
    // Filter pending posts
    const pendingPosts = (scheduledPosts || []).filter(
      (post) => post.status === "pending"
    );
    const totalPagesQueue = Math.ceil(pendingPosts.length / postsPerPage);
    const startIndexQueue = (currentPageQueue - 1) * postsPerPage;
    const currentQueuePosts = pendingPosts.slice(
      startIndexQueue,
      startIndexQueue + postsPerPage
    );

    return (
      <div className="px-4 py-3">
        {loading ? (
          <p className="text-center text-gray-500">Loading...</p>
        ) : error ? (
          <p className="text-center text-red-500">{error}</p>
        ) : pendingPosts.length === 0 ? (
          <div className="bg-red-100 text-red-700 text-sm p-2 rounded text-center mb-4">
            You have no posts scheduled
          </div>
        ) : (
          <>
            <div className="space-y-4">
              {currentQueuePosts.map((post, index) => (
                <div
                  key={index}
                  className="w-full text-left p-3 border border-gray-200 rounded hover:bg-gray-50"
                >
                  <div className="flex justify-between items-center">
                    <div className="text-gray-800 mb-1 font-semibold">
                      {dayjs
                        .utc(post.scheduled_datetime)
                        .tz(dayjs.tz.guess())
                        .format("MMM D, YYYY h:mm A")}
                    </div>
                    <div className="flex space-x-2">
                      <span
                        className="cursor-pointer hover:text-blue-700 transition-colors"
                        onClick={() =>
                          navigate(`/edit-scheduled-post/${post.id}`)
                        }
                      >
                        <MdOutlineEdit size={18} />
                      </span>
                      <span
                        className="cursor-pointer hover:text-red-600 transition-colors"
                        onClick={() => deleteScheduledPost(post.id)}
                      >
                        <MdOutlineDelete size={18} />
                      </span>
                    </div>
                  </div>
                  <div className="text-sm text-gray-500">
                    {getFirstSentence(post.post_text)}
                  </div>
                </div>
              ))}
            </div>
            {totalPagesQueue > 1 &&
              renderPagination(currentPageQueue, totalPagesQueue, setCurrentPageQueue)}
          </>
        )}
      </div>
    );
  };

  const renderPostedTab = () => {
    // Filter published posts
    const publishedPosts = (scheduledPosts || []).filter(
      (post) => post.status === "published"
    );
    const totalPagesPosted = Math.ceil(publishedPosts.length / postsPerPage);
    const startIndexPosted = (currentPagePosted - 1) * postsPerPage;
    const currentPostedPosts = publishedPosts.slice(
      startIndexPosted,
      startIndexPosted + postsPerPage
    );

    return (
      <div className="px-4 py-3">
        {loading ? (
          <p className="text-center text-gray-500">Loading...</p>
        ) : error ? (
          <p className="text-center text-red-500">{error}</p>
        ) : publishedPosts.length === 0 ? (
          <div className="bg-red-100 text-red-700 text-sm p-2 rounded text-center mb-4">
            You have no published posts
          </div>
        ) : (
          <>
            <div className="space-y-4">
              {currentPostedPosts.map((post, index) => (
                <div
                  key={index}
                  className="w-full text-left p-3 border border-gray-200 rounded hover:bg-gray-50"
                >
                  <div className="flex justify-between items-center">
                    <div className="text-gray-800 mb-1 font-semibold">
                      {dayjs
                        .utc(post.scheduled_datetime)
                        .tz(dayjs.tz.guess())
                        .format("MMM D, YYYY h:mm A")}
                    </div>
                    <div className="flex space-x-2">
                      <span
                        className="cursor-pointer hover:text-blue-700 transition-colors"
                        onClick={() =>
                          navigate(`/edit-scheduled-post/${post.id}`)
                        }
                      >
                        <MdOutlineEdit size={18} />
                      </span>
                      <span
                        className="cursor-pointer hover:text-red-600 transition-colors"
                        onClick={() => deleteScheduledPost(post.id)}
                      >
                        <MdOutlineDelete size={18} />
                      </span>
                    </div>
                  </div>
                  <div className="text-sm text-gray-500">
                    {getFirstSentence(post.post_text)}
                  </div>
                </div>
              ))}
            </div>
            {totalPagesPosted > 1 &&
              renderPagination(currentPagePosted, totalPagesPosted, setCurrentPagePosted)}
          </>
        )}
      </div>
    );
  };

  const renderTabContent = () => {
    if (activeTab === "queue") return renderQueueTab();
    if (activeTab === "posted") return renderPostedTab();
  };

  const [sidebarOpen, setSidebarOpen] = useState(false);

  return (
    <div className="relative steps-area steps-area-fixed min-h-screen">
      {/* Hamburger button: visible on small screens */}
      <div className="md:hidden py-4 px-2 flex justify-end">
        <button
          onClick={() => setSidebarOpen(true)}
          className="text-gray-500 hover:text-gray-700 focus:outline-none"
        >
          <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path
              fillRule="evenodd"
              d="M3 5h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2z"
              clipRule="evenodd"
            />
          </svg>
        </button>
      </div>

      {/* Mobile Sidebar Overlay */}
      {sidebarOpen && (
        <div className="fixed inset-0 z-40 md:hidden" role="dialog" aria-modal="true">
          {/* Overlay */}
          <div
            className="fixed inset-0 bg-black opacity-50"
            onClick={() => setSidebarOpen(false)}
          ></div>
          {/* Sidebar content */}
          <div className="fixed inset-y-0 left-0 w-full max-w-xs bg-white overflow-y-auto z-50">
            {/* Cancel button at the top right */}
            <div className="flex justify-end p-2 pb-0">
              <button
                onClick={() => setSidebarOpen(false)}
                className="text-gray-600 hover:text-gray-800 focus:outline-none"
              >
                <MdClose size={24} />
              </button>
            </div>
            {/* Sidebar content */}
            <div className="w-full flex flex-col border-r border-gray-200 bg-white">
              {/* Top Section: Profile & Dropdown */}
              <div
                className="relative flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50"
                ref={dropdownRef}
                onClick={() => setDropdownOpen(!dropdownOpen)}
              >
                <div className="flex items-center gap-2">
                  <img
                    src={profileInfo.profilePic || profile}
                    alt="Profile"
                    className="w-10 h-10 rounded-full object-cover"
                  />
                  <span className="font-semibold text-gray-800 text-sm">
                    {profileInfo.name || "User Name"}
                  </span>
                </div>
                <svg className="w-4 h-4 fill-current text-gray-500" viewBox="0 0 20 20">
                  <path d="M5.5 7l4 4 4-4" />
                </svg>
                {dropdownOpen && (
                  <div className="absolute mt-2 top-16 left-0 w-4/5 bg-white border border-gray-200 rounded shadow-md z-10">
                    <div className="px-4 py-3">
                      <div className="font-medium text-gray-800">
                        {profileInfo.name || "User Name"}
                      </div>
                      <div className="text-sm text-gray-500">
                        {profileInfo.email || "user@example.com"}
                      </div>
                    </div>
                    <hr className="border-gray-80" />
                    <a href="/billing-details">
                      <button className="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Billing Details
                      </button>
                    </a>
                    <a href="/update-password">
                      <button className="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Change Password
                      </button>
                    </a>
                    <button
                      onClick={handleLogout}
                      className="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    >
                      Logout
                    </button>
                  </div>
                )}
              </div>

              {/* Tabs for Queue/Posted */}
              <div className="px-4">
                <ul className="flex space-x-4 text-sm border-b border-gray-200">
                  <li>
                    <button
                      className={`px-0 py-2 font-medium focus:outline-none border-b-2 ${activeTab === "queue"
                          ? "text-[#0000FF] border-[#0000FF] hover:text-[#0000FF]"
                          : "text-gray-600 border-transparent hover:text-gray-600"
                        }`}
                      onClick={() => setActiveTab("queue")}
                    >
                      Queue
                    </button>
                  </li>
                  <li>
                    <button
                      className={`px-0 py-2 font-medium focus:outline-none border-b-2 ${activeTab === "posted"
                          ? "text-[#0000FF] border-[#0000FF] hover:text-[#0000FF]"
                          : "text-gray-600 border-transparent hover:text-gray-600"
                        }`}
                      onClick={() => setActiveTab("posted")}
                    >
                      Posted
                    </button>
                  </li>
                </ul>
              </div>
              {renderTabContent()}
            </div>
          </div>
        </div>
      )}
      {/* Desktop Sidebar */}
      <div className="hidden md:block">
        <div className="w-full flex flex-col border-r border-gray-200 bg-white">
          <div
            className="relative flex items-center justify-between p-2 cursor-pointer hover:bg-gray-50"
            ref={dropdownRef}
            onClick={() => setDropdownOpen(!dropdownOpen)}
          >
            <div className="flex items-center gap-2">
              <img
                src={profileInfo.profilePic || profile}
                alt="Profile"
                className="w-10 h-10 rounded-full object-cover"
              />
              <span className="font-semibold text-gray-800 text-sm">
                {profileInfo.name || "User Name"}
              </span>
            </div>
            <svg className="w-4 h-4 fill-current text-gray-500" viewBox="0 0 20 20">
              <path d="M5.5 7l4 4 4-4" />
            </svg>
            {dropdownOpen && (
              <div className="absolute mt-2 top-16 left-0 w-4/5 bg-white border border-gray-200 rounded shadow-md z-10">
                <div className="px-4 py-3">
                  <div className="font-medium text-gray-800">
                    {profileInfo.name || "User Name"}
                  </div>
                  <div className="text-sm text-gray-500">
                    {profileInfo.email || "user@example.com"}
                  </div>
                </div>
                <hr className="border-gray-80" />
                <a href="/billing-details">
                  <button className="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Billing Details
                  </button>
                </a>
                <a href="/update-password">
                  <button className="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Change Password
                  </button>
                </a>
                <button
                  onClick={handleLogout}
                  className="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  Logout
                </button>
              </div>
            )}
          </div>
          <div className="px-4">
            <ul className="flex space-x-4 text-sm border-b border-gray-200">
              <li>
                <button
                  className={`px-0 py-2 font-medium focus:outline-none border-b-2 ${activeTab === "queue"
                      ? "text-[#0000FF] border-[#0000FF] hover:text-[#0000FF]"
                      : "text-gray-600 border-transparent hover:text-gray-600"
                    }`}
                  onClick={() => setActiveTab("queue")}
                >
                  Queue
                </button>
              </li>
              <li>
                <button
                  className={`px-0 py-2 font-medium focus:outline-none border-b-2 ${activeTab === "posted"
                      ? "text-[#0000FF] border-[#0000FF] hover:text-[#0000FF]"
                      : "text-gray-600 border-transparent hover:text-gray-600"
                    }`}
                  onClick={() => setActiveTab("posted")}
                >
                  Posted
                </button>
              </li>
            </ul>
          </div>
          {renderTabContent()}
        </div>
      </div>
    </div>
  );
}
