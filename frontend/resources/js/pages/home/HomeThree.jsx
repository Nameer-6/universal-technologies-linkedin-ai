import Header from "@/components/Header";
import ImageGenerator from "@/components/ImageGenerator";
import { ReportIssueModal } from "@/components/ReportIssueModal";
import TypedText from "@/components/TypedText";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogOverlay,
    DialogTitle,
} from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
    useUnlockBodyScroll,
} from "@/components/ui/select";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { RichTextEditor } from "@mantine/rte";
import IconButton from "@mui/material/IconButton";
import {
    Calendar,
    CheckCircle,
    FileCheck,
    Lightbulb,
    ListTodo,
    RefreshCw,
    Send,
    Sparkles,
    Trash2,
    User,
    Zap,
} from "lucide-react";
import React, { useEffect, useRef, useState } from "react";
import { toast, Toaster } from "react-hot-toast";
import { BiLike, BiRepost, BiWorld } from "react-icons/bi";
import { FaSearch } from "react-icons/fa";
import { FaRegComment, FaRegImages } from "react-icons/fa6";
import { FiSend } from "react-icons/fi";
import { LiaShareSolid } from "react-icons/lia";
import { useNavigate } from "react-router-dom";
import thumb from "../../assets/img/heart.svg";
import Loader from "../../assets/img/loaders.gif";
import heart from "../../assets/img/thumb.svg";
import defaultProfile from "../../assets/img/user-default.jpg";
import ScheduledPostItem from "../../components/ScheduledPostItem";
import TrendingTopic from "../../components/TrendingTopic";
import { useCredits } from "../../lib/CreditsContext";

const HomeThree = () => {
  useUnlockBodyScroll();

  const navigate = useNavigate();
  const [userProfile, setUserProfile] = useState(null);
  const [profileLoading, setProfileLoading] = useState(true);

  // NEWS FILTERS (backend expects: category + country OR q)
  const [trendingField, setTrendingField] = useState("all");
  const [trendingCountry, setTrendingCountry] = useState("us");

  // NEWS DATA
  const [trendingTopics, setTrendingTopics] = useState([]);
  const [isRefreshing, setIsRefreshing] = useState(false);
  const [isFetchingInitial, setIsFetchingInitial] = useState(false);
  const trendingContainerRef = useRef(null);
  const [newsError, setNewsError] = useState(null);

  // EDITOR & GENERATION
  const [customTopic, setCustomTopic] = useState("");
  const [editorContent, setEditorContent] = useState("");
  const [isGenerating, setIsGenerating] = useState(false);
  const [isImgGenerating, setIsImgGenerating] = useState(false);
  const [generatedImagePrompt, setGeneratedImagePrompt] = useState("");
  const [usedPersona, setUsedPersona] = useState("");
  const [customPrompt, setCustomPrompt] = useState("");
  const [isReportModalOpen, setIsReportModalOpen] = useState(false);
  // MEDIA
  const [mediaData, setMediaData] = useState("");
  const [mediaType, setMediaType] = useState("");
  const [mediaFile, setMediaFile] = useState(null);

  // SCHEDULING
  const [scheduledPosts, setScheduledPosts] = useState([]);
  const [isScheduleModalOpen, setIsScheduleModalOpen] = useState(false);
  const [scheduleDate, setScheduleDate] = useState("");
  const [scheduleTime, setScheduleTime] = useState("");
  const [editingPostId, setEditingPostId] = useState(null);

  // UI
  const [activeTab, setActiveTab] = useState("create");
  const [isPublishing, setIsPublishing] = useState(false);
  const [windowWidth, setWindowWidth] = useState(window.innerWidth);
  const [charCount, setCharCount] = useState(0);
  const postPreviewRef = useRef(null);

  // PERSONA / LANGUAGE / SIZE
  const [selectedInfluencer, setSelectedInfluencer] = useState("");
  const [selectedInfluencerField, setSelectedInfluencerField] = useState("Random");
  const [charLimit, setCharLimit] = useState("");
  const [selectedLanguage, setSelectedLanguage] = useState("en");
  const [addUniversalTechCredit, setAddUniversalTechCredit] = useState(false);

  // SEARCH
  const [searchQuery, setSearchQuery] = useState("");
  const [hasSearched, setHasSearched] = useState(false);

  const creditHtml = "\n\nPowered by Universal Technologies";
  const { fetchCredits } = useCredits();

  // Fetch profile & ensure LinkedIn connection
  useEffect(() => {
    const fetchProfile = async () => {
      try {
        const token = localStorage.getItem('auth_token');
        if (!token) {
          navigate("/login");
          return;
        }
        
        const profileRes = await fetch("/api/profile", { 
          credentials: "include",
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        });
        
        if (profileRes.status === 401) {
          setUserProfile(null);
          localStorage.removeItem('auth_token');
          toast.error("Session expired. Please sign in again.");
          navigate("/login");
          return;
        }
        let profileData = await profileRes.json();
        
        // Clean corrupted response if needed
        if (typeof profileData === 'string' && profileData.includes('<br /><b>Deprecated</b>')) {
          const jsonStartIndex = profileData.indexOf('{');
          const jsonEndIndex = profileData.lastIndexOf('}');
          if (jsonStartIndex !== -1 && jsonEndIndex !== -1 && jsonEndIndex > jsonStartIndex) {
            const jsonString = profileData.substring(jsonStartIndex, jsonEndIndex + 1);
            try {
              profileData = JSON.parse(jsonString);
            } catch (e) {
              console.error("Failed to parse cleaned profile JSON:", e);
              setUserProfile(null);
              return;
            }
          }
        }
        
        if (profileData.error) {
          setUserProfile(null);
          return;
        }
        setUserProfile(profileData);

        // Optional: verify LinkedIn connection
        try {
          const checkRes = await fetch("/api/check-linkedin", { 
            credentials: "include",
            headers: {
              'Authorization': `Bearer ${token}`,
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            }
          });
          if (!checkRes.ok) throw new Error("Failed to check LinkedIn connection");
          let checkData = await checkRes.json();
          
          // Clean corrupted response if needed
          if (typeof checkData === 'string' && checkData.includes('<br /><b>Deprecated</b>')) {
            const jsonStartIndex = checkData.indexOf('{');
            const jsonEndIndex = checkData.lastIndexOf('}');
            if (jsonStartIndex !== -1 && jsonEndIndex !== -1 && jsonEndIndex > jsonStartIndex) {
              const jsonString = checkData.substring(jsonStartIndex, jsonEndIndex + 1);
              try {
                checkData = JSON.parse(jsonString);
              } catch (e) {
                console.error("Failed to parse cleaned LinkedIn check JSON:", e);
                throw new Error("Failed to parse LinkedIn connection response");
              }
            }
          }
          
          if (!checkData.connected) {
            toast.warn("Please connect your LinkedIn account.");
            // Don't redirect automatically - let user click the button
          } else {
            console.log("✅ LinkedIn connected:", checkData);
          }
        } catch (linkedinError) {
          console.warn("LinkedIn connection check failed:", linkedinError);
          // Don't set userProfile to null for LinkedIn check failures
        }
      } catch (error) {
        console.error("Profile fetch error:", error);
        setUserProfile(null);
      } finally {
        setProfileLoading(false);
      }
    };
    fetchProfile();
  }, [navigate]);

  // Window resize
  useEffect(() => {
    const handleResize = () => setWindowWidth(window.innerWidth);
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  // Scheduled posts
  useEffect(() => {
    if (userProfile) fetchScheduledPosts();
  }, [userProfile]);

  const fetchScheduledPosts = async () => {
    try {
      const res = await fetch("/api/user-scheduled-posts", { credentials: "include" });
      const data = await res.json();
      if (res.ok) {
        setScheduledPosts(data.scheduled_posts || []);
      } else {
        toast.error(data.error || "Error fetching scheduled posts.");
      }
    } catch (err) {
      toast.error("Error fetching scheduled posts.");
    }
  };

  // Fetch trending topics from new backend (category+country or q)
  const fetchTrending = async () => {
    setIsFetchingInitial(true);
    setNewsError(null);
    try {
      const payload = {};
      if (searchQuery.trim()) {
        payload.q = searchQuery.trim();
      } else {
        if (trendingField) payload.category = trendingField; // e.g., "business", "all"
        if (trendingCountry) payload.country = trendingCountry; // e.g., "us", "global"
      }

      // Add timeout to prevent hanging
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout

      const res = await fetch("/api/generate-options", {
        method: "POST",
        headers: { 
          "Content-Type": "application/json",
          "Accept": "application/json",
          'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
        },
        credentials: "include",
        body: JSON.stringify(payload),
        signal: controller.signal,
      });

      clearTimeout(timeoutId);

      const data = await res.json().catch(() => ({}));
      if (!res.ok) {
        setTrendingTopics([]);
        setNewsError(
          `Couldn't fetch news. (${res.status}) Try again or change filters.`
        );
      } else {
        const topics = Array.isArray(data?.trending_topics) ? data.trending_topics : [];
        // De-dup on URL just in case
        const unique = topics.filter(
          (article, index, self) => index === self.findIndex((a) => a.url === article.url)
        );
        setTrendingTopics(unique);
        if (unique.length === 0) {
          setNewsError("No headlines for these filters right now. Try a different category/country or a search.");
        }
      }
    } catch (err) {
      setTrendingTopics([]);
      if (err.name === 'AbortError') {
        setNewsError("Request timed out. The news service is taking longer than expected. Please try again.");
      } else {
        setNewsError("Couldn't fetch news. Check your connection and try again.");
      }
    } finally {
      setIsFetchingInitial(false);
    }
  };

  // Initial + when filters change (category/country)
  useEffect(() => {
    setTrendingTopics([]);
    setHasSearched(false);
    setSearchQuery("");
    fetchTrending();
  }, [trendingField, trendingCountry]);

  // Refresh
  const handleRefreshTrending = async () => {
    setIsRefreshing(true);
    setNewsError(null);
    try {
      await fetchTrending();
    } catch (err) {
      toast.error("Couldn’t refresh trending topics. See console for details.");
    } finally {
      setIsRefreshing(false);
    }
  };

  // Search
  const handleSearch = () => {
    if (!searchQuery.trim()) {
      setHasSearched(false);
      setTrendingTopics([]);
      return toast.error("Please enter a keyword to search.");
    }
    setHasSearched(true);
    setTrendingTopics([]);
    fetchTrending();
  };

  // Clear search if user empties the box
  useEffect(() => {
    if (!searchQuery.trim()) {
      setHasSearched(false);
      setTrendingTopics([]);
    }
  }, [searchQuery]);

  // Sign in
  const handleSignIn = async () => {
    try {
      const token = localStorage.getItem('auth_token');
      if (!token) {
        toast.error("Please login first");
        navigate("/login");
        return;
      }
      
      // In development mode, the backend creates a mock LinkedIn profile and redirects
      // Just redirect to the LinkedIn login endpoint which will handle the mock creation
      window.location.href = "/api/linkedin-login";
    } catch (error) {
      console.error("LinkedIn login error:", error);
      toast.error("Error connecting to LinkedIn");
    }
  };

  // Generate post
  const autoGeneratePost = async (topic) => {
    if (!topic) {
      toast.error("Please enter a topic to generate content.");
      return;
    }
    try {
      setIsGenerating(true);
      setEditorContent("");
      setUsedPersona("");
      setCustomPrompt("");

      const res = await fetch("/api/generate-post", {
        method: "POST",
        headers: { 
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        credentials: "include",
        body: JSON.stringify({
          topic,
          influencer: selectedInfluencer,
          field: selectedInfluencerField,
          charLimit: charLimit ? parseInt(charLimit, 10) : undefined,
          language: selectedLanguage || "en",
        }),
      });

      const data = await res.json();
      if (res.ok && data.post) {
        setEditorContent(data.post);
        const plain = data.post.replace(/<[^>]*>/g, "");
        setCharCount(plain.length);
        setUsedPersona(data.inspired_by || "");
        setCustomPrompt(data.custom_prompt || "");
        if (typeof fetchCredits === "function") fetchCredits();
      } else if (data.error && data.error.toLowerCase().includes("credit")) {
        toast.error(
          data.error ||
          "You have used all your credits. Please purchase or renew your plan to continue generating posts.",
          { duration: 7000 }
        );
        setEditorContent("");
        setUsedPersona("");
        setCustomPrompt("");
        setCharCount(0);
      } else {
        toast.error(data.error || "Error generating post content.");
      }
    } catch (err) {
      toast.error("Error generating post.");
    } finally {
      setIsGenerating(false);
    }
  };

  // Editor change (convert **bold** + *italic*)
  const handleEditorChange = (value) => {
    const formattedValue = value
      .replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>")
      .replace(/\*(.*?)\*/g, "<em>$1</em>");
    setEditorContent(formattedValue);
    const plainText = formattedValue.replace(/<[^>]*>/g, "");
    setCharCount(plainText.length);
  };

  // Media select (image/video/pdf)
  const handleMediaChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    if (mediaData) URL.revokeObjectURL(mediaData);

    setMediaData("");
    setMediaType("");
    setMediaFile(null);

    const validTypes = [
      "image/jpeg",
      "image/png",
      "image/gif",
      "video/mp4",
      "video/quicktime",
      "video/x-msvideo",
      "application/pdf",
    ];

    if (!validTypes.includes(file.type)) {
      toast.error("Unsupported file type. Please upload JPEG, PNG, GIF, MP4, MOV, AVI, or PDF.");
      return;
    }

    const objectUrl = URL.createObjectURL(file);
    let type = "";
    if (file.type.startsWith("image/")) type = "image";
    else if (file.type.startsWith("video/")) type = "video";
    else if (file.type === "application/pdf") type = "pdf";

    setMediaType(type);
    setMediaData(objectUrl);
    setMediaFile(file);
  };

  // Publish now
  const handlePublishNow = async () => {
    if (
      !editorContent ||
      editorContent.trim() === "" ||
      editorContent.trim() === "<p><br></p>"
    ) {
      toast.error("Your post content is empty.");
      return;
    }
    setIsPublishing(true);
    if (charCount > 3000) {
      toast.error(`Your post is ${charCount} characters; please shorten to 3000 or less.`);
      setIsPublishing(false);
      return;
    }
    try {
      const formData = new FormData();
      formData.append("topic", customTopic || "");
      formData.append("schedule", "now");
      const contentToSend = addUniversalTechCredit ? editorContent + creditHtml : editorContent;
      formData.append("post", contentToSend);

      if (mediaFile) {
        formData.append("media", mediaFile);
        formData.append("media_type", mediaType === "video" ? "video/mp4" : mediaFile.type);
      } else if (mediaType === "image" && mediaData && typeof mediaData === "string" && !mediaFile) {
        formData.append("media_url", mediaData);
        formData.append("media_type", "image/jpeg");
      }

      const res = await fetch("/api/linkedin-post", {
        method: "POST",
        credentials: "include",
        body: formData,
      });
      const data = await res.json();
      if (res.ok) {
        toast.success(data.message || "Post published successfully!");
        setEditorContent("");
        setMediaData("");
        setMediaType("");
        setMediaFile(null);
        setCustomTopic("");
        setUsedPersona("");
        setCustomPrompt("");
      } else {
        toast.error(data.error || "Error publishing post.");
      }
    } catch (err) {
      toast.error("Error publishing post.");
    } finally {
      setIsPublishing(false);
    }
  };

  // Update scheduled post
  const handleUpdatePost = () => {
    if (
      !editorContent ||
      editorContent.trim() === "" ||
      editorContent.trim() === "<p><br></p>"
    ) {
      toast.error("Your post content is empty.");
      return;
    }
    setIsPublishing(true);
    let cleanedContent = editorContent.replace(/^VariationTag\s*=\s*\w+\s*$/im, "");
    const formData = new FormData();
    formData.append("post_text", cleanedContent);
    formData.append("id", editingPostId);
    formData.append("topic", customTopic);
    if (scheduleDate && scheduleTime) {
      const [year, month, day] = scheduleDate.split("-");
      const [hour, minute] = scheduleTime.split(":");
      // Send the datetime in YYYY-MM-DD HH:MM:SS format without timezone conversion
      const scheduledDatetime = `${scheduleDate} ${scheduleTime}:00`;
      formData.append("scheduled_datetime", scheduledDatetime);
    }
    if (mediaFile) {
      formData.append("media", mediaFile);
      formData.append("media_type", mediaType === "video" ? "video/mp4" : mediaFile.type);
    } else if (!mediaData) {
      formData.append("remove_media", "true");
    }
    fetch("/api/scheduled-posts/update", {
      method: "POST",
      credentials: "include",
      body: formData,
    })
      .then(async (res) => {
        const data = await res.json();
        if (res.ok) {
          toast.success(data.message || "Scheduled post updated successfully!");
          setEditorContent("");
          setCustomTopic("");
          setUsedPersona("");
          setCustomPrompt("");
          setMediaData("");
          setMediaType("");
          setMediaFile(null);
          setEditingPostId(null);
          fetchScheduledPosts();
        } else {
          toast.error(data.error || "Error updating post.");
        }
      })
      .catch(() => {
        toast.error("Error updating post.");
      })
      .finally(() => {
        setIsPublishing(false);
      });
  };

  const removeEmojisFromContent = (text) =>
    text.replace(
      /(?:[\u2700-\u27BF]|[\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDE4F]|\uD83D[\uDE80-\uDEFF]|\uD83E[\uDD00-\uDDFF])/g,
      ""
    );

  // Schedule post
  const handleSchedulePost = async () => {
    if (!scheduleDate || !scheduleTime) {
      toast.error("Please select both date and time to schedule your post.");
      return;
    }
    if (
      !editorContent ||
      editorContent.trim() === "" ||
      editorContent.trim() === "<p><br></p>"
    ) {
      toast.error("Your post content is empty.");
      return;
    }
    setIsPublishing(true);
    try {
      const formData = new FormData();
      formData.append("topic", customTopic || "");
      formData.append("schedule", "scheduled");
      const scheduledContent = addUniversalTechCredit
        ? editorContent + creditHtml
        : editorContent;
      formData.append("post", scheduledContent);

      // Convert local date+time (from pickers) to UTC ISO
      // Ex: scheduleDate = "2024-08-27", scheduleTime = "17:00"
      const localDateTime = new Date(`${scheduleDate}T${scheduleTime}`);
      const utcISOString = localDateTime.toISOString(); // "2024-08-27T12:00:00.000Z"
      formData.append("scheduled_datetime", utcISOString);

      // ***** ADD THIS LINE: send user's timezone string *****
      formData.append("timezone", Intl.DateTimeFormat().resolvedOptions().timeZone);

      if (mediaFile) {
        formData.append("media", mediaFile);
        formData.append(
          "media_type",
          mediaType === "video" ? "video/mp4" : mediaFile.type
        );
      } else if (
        mediaType === "image" &&
        mediaData &&
        typeof mediaData === "string" &&
        !mediaFile
      ) {
        formData.append("media_url", mediaData);
        formData.append("media_type", "image/jpeg");
      }

      const res = await fetch("/api/linkedin-post", {
        method: "POST",
        credentials: "include",
        body: formData,
      });
      const data = await res.json();
      if (res.ok) {
        toast.success(data.message || "Post scheduled successfully!");
        setEditorContent("");
        setMediaData("");
        setMediaType("");
        setMediaFile(null);
        setCustomTopic("");
        setUsedPersona("");
        setCustomPrompt("");
        setIsScheduleModalOpen(false);
        fetchScheduledPosts();
      } else {
        toast.error(data.error || "Error scheduling post.");
      }
    } catch (err) {
      toast.error("Error scheduling post.");
    } finally {
      setIsPublishing(false);
    }
  };


  // Delete scheduled/published post
  const handleDeletePublishedPost = async (postId) => {
    try {
      const formData = new FormData();
      formData.append("id", postId);
      const res = await fetch("/api/scheduled-posts/delete", {
        method: "POST",
        credentials: "include",
        body: formData,
      });
      const data = await res.json();
      if (res.ok) {
        toast.success(data.message || "Post deleted successfully!");
        setScheduledPosts((prev) => prev.filter((p) => p.id !== postId));
      } else {
        toast.error(data.error || "Error deleting post.");
      }
    } catch {
      toast.error("Network error deleting post.");
    }
  };

  // Clean loaded post text from DB
  const cleanLoadedPost = (text) => {
    if (!text) return "";
    let cleaned = text;
    cleaned = cleaned.replace(/(\r\n|\n|\r)/g, "<br>");
    cleaned = cleaned.replace(/^(<br>\s*)+/, "");
    cleaned = cleaned.replace(/(<br>\s*)+$/, "");
    cleaned = cleaned.replace(/(<br>\s*){2,}/g, "<br>");
    return cleaned.trim();
  };

  // Edit scheduled post
  const handleEditScheduledPost = async (post) => {
    try {
      const res = await fetch(`/api/get-full-post?id=${post.id}`, { credentials: "include" });
      const data = await res.json();
      if (res.ok && data.fullContent) {
        setEditorContent(cleanLoadedPost(data.fullContent));
      } else {
        setEditorContent(cleanLoadedPost(post.post_text || post.content || ""));
      }
      if (post.media_url) {
        setMediaData(post.media_url);
        setMediaType(post.media_type || "");
        setMediaFile(null);
      } else {
        setMediaData("");
        setMediaType("");
        setMediaFile(null);
      }
    } catch (error) {
      setEditorContent(cleanLoadedPost(post.post_text || post.content || ""));
    }
    setCustomTopic(post.topic || "");
    setEditingPostId(post.id);
    if (post.scheduled_datetime) {
      // Assume the datetime is stored in UTC and convert to local
    const dt = new Date(post.scheduled_datetime + 'Z'); // Add Z to indicate UTC
    const localDate = dt.toLocaleDateString('en-CA'); // YYYY-MM-DD format
    const localTime = dt.toLocaleTimeString('en-GB', {
      hour12: false,
      hour: '2-digit',
      minute: '2-digit'
    });
    setScheduleDate(localDate);
    setScheduleTime(localTime);
    }
    setActiveTab("create");
    toast("Post loaded for editing.");
  };

  // Create post from a trending topic
  const handleCreateFromTopic = (title, description) => {
    const prompt = `${title}: ${description || ""}`;
    setCustomTopic(prompt);
    autoGeneratePost(prompt).then(() => {
      if (postPreviewRef.current) {
        postPreviewRef.current.scrollIntoView({ behavior: "smooth", block: "start" });
      }
    });
  };

  if (profileLoading) {
    return (
      <div className="flex items-center justify-center h-screen">
        <img src={Loader} className="w-[100px] h-[100px] object-contain" />
      </div>
    );
  }

  if (!userProfile) {
    return (
      <div className="min-h-screen flex flex-col items-center justify-center px-4 text-center">
        <Toaster />
        <h1 className="text-3xl font-bold mb-4">Welcome!</h1>
        <p className="mb-6">Sign in with LinkedIn to use this tool.</p>
        <Button className="bg-[#0000ff] text-white hover:bg-[#0000ffc4]" onClick={handleSignIn}>
          Sign in with LinkedIn
        </Button>
      </div>
    );
  }

  const rtlLanguages = ["ar", "ur", "fa", "he"];
  const isRTL = rtlLanguages.includes(selectedLanguage);

  return (
    <div className="max-w-7xl mx-auto px-4 pb-10 relative">
      <Toaster />
      <div className="mb-10">
        <Header />
      </div>
      <div className="absolute top-10 left-10 w-48 h-48 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow"></div>
      <div className="absolute bottom-10 right-10 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow"></div>
      <div className="relative">
        <div className="text-center mb-12 main-headings">
          <div className="inline-block relative">
            <span className="absolute lg:-left-12 md:-left-12 left-0 -top-8 text-4xl animate-bounce-slow">✨</span>
            <h2 className="font-bold mb-2 relative inline-block">
              <span className="bg-gradient-to-r from-linkedin-primary to-blue-700 bg-clip-text text-transparent">
                Start Creating, Scheduling
              </span>
            </h2>
          </div>
          <div className="relative">
            <h1 className="font-bold mt-3 mb-4 relative inline-block">
              Your <span style={{ color: "#ff0000" }}>LinkedIn </span>Posts Effortlessly
              <span className="absolute -top-4">
                <Sparkles className="w-6 h-6 text-yellow-400 animate-pulse-slow" />
              </span>
            </h1>
            <h3 className="font-bold">
              <TypedText />
            </h3>
          </div>
        </div>

        <Tabs value={activeTab} onValueChange={setActiveTab} className="w-full">
          <TabsList className="grid grid-cols-2 lg:w-1/2 w-100 mx-auto mb-8 main_tabs">
            <TabsTrigger
              value="create"
              className="font-bold data-[state=active]:bg-linkedin-primary data-[state=active]:text-white data-[state=active]:rounded-[5px] flex p-[12px]"
            >
              <Zap className="mr-2 h-4 w-4" />
              Create Post
            </TabsTrigger>
            <TabsTrigger
              value="scheduled"
              className="data-[state=active]:bg-linkedin-primary font-bold data-[state=active]:text-white data-[state=active]:rounded-[5px] flex p-[12px]"
            >
              <Calendar className="mr-2 h-4 w-4" />
              Scheduled Posts
            </TabsTrigger>
          </TabsList>

          {/* CREATE TAB */}
          <TabsContent value="create" className="space-y-4 animate-fade-in">
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
              <div className="lg:col-span-2 order-1">
                <Card className="overflow-hidden border shadow-lg transform transition-all duration-300 hover:shadow-xl">
                  <div className="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
                    <h2 className="text-xl font-semibold flex items-center gap-2 text-gray-800">
                      <Lightbulb className="text-[#f2cf19]" size={20} />
                      Generate Your LinkedIn Post:
                    </h2>
                  </div>
                  <div className="p-6">
                    <form
                      onSubmit={(e) => {
                        e.preventDefault();
                        autoGeneratePost(customTopic);
                      }}
                    >
                      <div className="flex mb-4 gap-4 res-custom-input">
                        <Input
                          value={customTopic}
                          onChange={(e) => setCustomTopic(e.target.value)}
                          placeholder="Write a custom topic to generate post"
                          className="p-2 border rounded focus:outline-none focus:ring-linkedin-primary focus-visible:ring-linkedin-primary"
                        />
                        <Button
                          type="submit"
                          className="flex gap-2 items-center bg-[#0000ff] hover:bg-[#0000ffc4] font-bold"
                          disabled={isGenerating}
                        >
                          {isGenerating ? (
                            <>
                              <div className="animate-spin rounded-full h-4 w-4 border-2 border-t-transparent"></div>
                              <span>Generating...</span>
                            </>
                          ) : editorContent.trim() !== "" ? (
                            <>
                              <Sparkles size={16} className="group-hover:animate-pulse" />
                              <span>Regenerate</span>
                            </>
                          ) : (
                            <>
                              <Sparkles size={16} className="group-hover:animate-pulse" />
                              <span>Generate</span>
                            </>
                          )}
                        </Button>
                      </div>
                    </form>

                    <div className="border rounded-md overflow-hidden bg-white shadow transition-all duration-300 hover:shadow-md">
                      <RichTextEditor
                        value={editorContent}
                        onChange={handleEditorChange}
                        placeholder="Your AI-generated or custom text will appear here..."
                        controls={[
                          ["h1", "h2", "h3", "blockquote", "code"],
                          ["bold", "italic", "underline", "strike"],
                          ["link"],
                          ["alignLeft", "alignCenter", "alignRight"],
                        ]}
                        className={isRTL ? "text-right" : "text-left"}
                      />

                    </div>

                    <div className="flex items-center justify-center gap-2 mt-5">
                      <span className="text-xs text-gray-500 text-center max-w-600">
                        <b>Please note</b>: AI is still evolving and may occasionally make mistakes, as AI technology is
                        continually improving worldwide. If you encounter any issues or inaccuracies, kindly report them
                        here. We appreciate your feedback!
                        <a
                          className="ml-2 text-red-500 font-semibold cursor-pointer hover:underline transition hover:text-red-700"
                          onClick={() => setIsReportModalOpen(true)}
                        >
                          Report Issue
                        </a>
                      </span>
                    </div>

                    {/* Media bar */}
                    <div className="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 px-4 rounded-lg border shadow-sm flex py-4 justify-between items-center min-h-[65px] overflow-hidden">
                      <div className="flex flex-col md:flex-row items-center justify-between h-full  w-full">
                        <div className="flex items-center gap-4 h-full">
                          <label htmlFor="fileInput" className="flex gap-x-2 text-xs items-center cursor-pointer">
                            <FaRegImages size={24} />
                            Insert Media
                          </label>
                          <input
                            id="fileInput"
                            type="file"
                            accept="image/*,video/*,application/pdf"
                            className="hidden"
                            onClick={(e) => {
                              e.currentTarget.value = null;
                            }}
                            onChange={handleMediaChange}
                          />

                          {mediaData && (
                            <div className="relative flex-shrink-0 h-full w-[48px]">
                              {mediaType === "image" ? (
                                <img
                                  src={mediaData}
                                  alt="preview"
                                  className="h-full w-auto object-contain rounded border"
                                  style={{ maxWidth: 48 }}
                                />
                              ) : mediaType === "video" ? (
                                <video
                                  src={mediaData}
                                  className="h-full w-auto object-contain rounded border"
                                  muted
                                  style={{ maxWidth: 48 }}
                                />
                              ) : mediaType === "pdf" ? (
                                <div className="w-[48px] h-full flex items-center justify-center bg-gray-100 rounded border text-xs">
                                  PDF
                                </div>
                              ) : null}

                              <IconButton
                                onClick={() => {
                                  setMediaData("");
                                  setMediaType("");
                                  setMediaFile(null);
                                  setGeneratedImagePrompt("");
                                }}
                                style={{
                                  position: "absolute",
                                  top: 0,
                                  right: 0,
                                  background: "#0000008c",
                                  width: "100%",
                                  height: "100%",
                                  color: "#a5a5a5",
                                  zIndex: 1,
                                  borderRadius: "0",
                                }}
                                size="small"
                                aria-label="remove attachment"
                              >
                                <Trash2 fontSize="small" className="hover:text-white" />
                              </IconButton>
                            </div>
                          )}
                        </div>

                        <div className="flex flex-col gap-1 sm:flex-row items-center w-full sm:w-auto">
                          <div className="mt-2 sm:mt-0 flex items-center text-sm text-gray-700 gap-2">
                            <User size={16} className="text-linkedin-primary" />
                            <span className="font-semibold">Writing style is inspired by:</span>
                          </div>
                          {usedPersona && (
                            <div className="flex flex-wrap gap-2 text-sm text-gray-700">
                              <span className="font-semibold" style={{ color: "#0014ff" }}>
                                {usedPersona}
                              </span>
                            </div>
                          )}
                        </div>
                      </div>
                    </div>

                    <div className="mt-5">
                      <div className="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div className="mt-3 flex items-center">
                          <input
                            id="universalTechCredit"
                            type="checkbox"
                            checked={addUniversalTechCredit}
                            onChange={() => setAddUniversalTechCredit(!addUniversalTechCredit)}
                            className="h-4 w-4 text-linkedin-primary border-gray-300 rounded"
                          />
                          <label htmlFor="universalTechCredit" className="ml-2 text-sm text-gray-600">
                            Give Credit to Universal Technologies
                          </label>
                        </div>
                        <div className="flex flex-col b-0 gap-2 sm:flex-row sm:items-center sm:gap-4 w-full sm:w-auto">
                          <p className="text-xs font-semibold text-gray-700">Content length: {charCount} characters</p>
                        </div>
                      </div>
                    </div>

                    <div className="mt-6">
                      {customPrompt && (
                        <div className="p-2 border rounded bg-gray-50 text-sm text-gray-700 mb-3">
                          <strong>Custom Prompt:</strong>
                          <p>{customPrompt}</p>
                        </div>
                      )}
                    </div>

                    <div className="flex items-center justify-between mt-8 res-action-btns gap-4">
                      <Button
                        variant="outline"
                        className="hover:bg-blue-50 hover:text-blue-600 transition-colors clear"
                        onClick={() => setEditorContent(removeEmojisFromContent(editorContent))}
                      >
                        Remove Emojis
                      </Button>
                      <Button
                        variant="outline"
                        className="hover:bg-red-50 hover:text-red-600 transition-colors clear"
                        onClick={() => {
                          setEditorContent("");
                          setCustomTopic("");
                          setUsedPersona("");
                          setCustomPrompt("");
                          setMediaData("");
                          setMediaType("");
                          setMediaFile(null);
                          setEditingPostId(null);
                        }}
                      >
                        Clear
                      </Button>
                      <div className="flex gap-2 w-full justify-end">
                        <Button
                          variant="outline"
                          className="border text-linkedin-primary hover:bg-[#0000ff] hover:text-white transition-colors flex gap-2 items-center font-bold schedule"
                          onClick={() => setIsScheduleModalOpen(true)}
                        >
                          <Calendar size={16} />
                          <span>Schedule</span>
                        </Button>
                        <Button
                          className="bg-linkedin-primary hover:bg-[#0000ffc4] transition-bg flex gap-2 items-center group text-white font-bold post-now"
                          onClick={() => {
                            if (editingPostId) {
                              handleUpdatePost();
                            } else if (activeTab === "scheduled") {
                              handleSchedulePost();
                            } else {
                              handlePublishNow();
                            }
                          }}
                          disabled={isPublishing}
                        >
                          {isPublishing ? (
                            editingPostId ? (
                              "Updating..."
                            ) : (
                              "Publishing..."
                            )
                          ) : (
                            <>
                              <span>{editingPostId ? "Update" : "Post Now"}</span>
                              <Send size={16} className="group-hover:translate-x-1 transition-transform" />
                            </>
                          )}
                        </Button>
                      </div>
                    </div>
                  </div>
                </Card>

                {/* IMAGE GENERATOR (kept as-is, uses /api/image-idea + /api/dalle) */}
                <ImageGenerator
                  editorContent={editorContent}
                  customTopic={customTopic}
                  mediaData={mediaData}
                  mediaType={mediaType}
                  setMediaData={setMediaData}
                  setMediaType={setMediaType}
                  setMediaFile={setMediaFile}
                  setGeneratedImagePrompt={setGeneratedImagePrompt}
                  isImgGenerating={isImgGenerating}
                  setIsImgGenerating={setIsImgGenerating}
                  disabled={!editorContent || editorContent.trim() === "" || editorContent.trim() === "<p><br></p>"}
                />

                {/* POST PREVIEW */}
                <div
                  className="mt-5 rounded-sm overflow-hidden border shadow-lg transform transition-all duration-300 hover:shadow-xl bg-white"
                  ref={postPreviewRef}
                >
                  <div className="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
                    <h2 className="text-xl font-semibold flex items-center gap-2 text-gray-800">
                      <Lightbulb className="text-[#f2cf19]" size={20} />
                      Post Preview:
                    </h2>
                  </div>
                  <div className="p-6">
                    <div className="flex items-center">
                      <img
                        src={userProfile?.profilePic || defaultProfile}
                        alt="User Avatar"
                        className="w-10 h-10 rounded-full object-cover"
                      />
                      <div className="ml-3 w-4/5 min-w-0">
                        <div className="font-semibold text-md text-gray-800 truncate overflow-hidden whitespace-nowrap">
                          {userProfile?.name || "Your Name"}
                        </div>
                        <div className="text-sm text-gray-800 truncate overflow-hidden whitespace-nowrap">
                          {userProfile?.headline || "Your Headline"}
                        </div>
                        <div className="flex items-center text-xs text-gray-400 gap-1 mt-1">
                          <span className="text-gray-600">Now</span>
                          <span className="text-gray-700" style={{ fontSize: "12px" }}>
                            &#8226;
                          </span>
                          <BiWorld size={16} color="#636363" />
                        </div>
                      </div>
                    </div>

                    <div
                      className={`mt-3 text-sm text-gray-700 post-preview-content ${isRTL ? "text-right" : "text-left"}`}
                      dangerouslySetInnerHTML={{
                        __html: (() => {
                          if (!editorContent || editorContent.trim() === "<p><br></p>") {
                            return `<p class="mb-1">Start writing and your post will appear here..</p>`;
                          }
                          const base = editorContent;
                          return addUniversalTechCredit ? base + creditHtml : base;
                        })(),
                      }}
                    />
                    {mediaData && (
                      <div className="mt-3 relative">
                        {mediaType === "video" ? (
                          <video controls src={mediaData} style={{ maxWidth: "100%", maxHeight: "460px", margin: "auto" }} />
                        ) : mediaType === "pdf" ? (
                          <embed src={mediaData} type="application/pdf" style={{ width: "100%", maxHeight: "460px", margin: "auto" }} />
                        ) : (
                          <img src={mediaData} alt="Selected" style={{ maxWidth: "100%", maxHeight: "710px", margin: "auto" }} />
                        )}
                      </div>
                    )}

                    <div className="flex items-center text-xs text-gray-500 mt-3 justify-between">
                      <div className="flex items-center">
                        <img src={heart} alt="Heart Icon" className="w-5 h-5 -ml-1" />
                        <img src={thumb} alt="Like Icon" className="w-5 h-5 ml-[-7px]" />
                        <span className="ml-1">1.2k</span>
                      </div>
                      <div className="flex items-center gap-1">
                        <span>155 comments</span>
                        <span>•</span>
                        <span>45 reposts</span>
                      </div>
                    </div>
                    <hr className="mt-3 mb-2 border-[#dddddd]" />
                    {windowWidth < 450 ? (
                      <div className="flex justify-around post-actions">
                        <span className="flex items-center gap-1 hover:bg-gray-200 p-1 rounded">
                          <BiLike size={20} />
                          <span>Like</span>
                        </span>
                        <span className="flex items-center gap-1 hover:bg-gray-200 p-1 rounded">
                          <FaRegComment size={20} />
                          <span>Comment</span>
                        </span>
                        <span className="flex items-center gap-1 hover:bg-gray-200 p-1 rounded">
                          <LiaShareSolid size={20} />
                          <span>Share</span>
                        </span>
                      </div>
                    ) : (
                      <div className="flex justify-around post-actions">
                        <span className="flex items-center hover:bg-gray-200 p-1 rounded">
                          <BiLike size={20} />
                          <span>Like</span>
                        </span>
                        <span className="flex items-center hover:bg-gray-200 p-1 rounded">
                          <FaRegComment size={20} />
                          <span>Comment</span>
                        </span>
                        <span className="flex items-center hover:bg-gray-200 p-1 rounded">
                          <BiRepost size={20} />
                          <span>Repost</span>
                        </span>
                        <span className="flex items-center hover:bg-gray-200 p-1 rounded">
                          <FiSend size={20} />
                          <span>Send</span>
                        </span>
                      </div>
                    )}
                  </div>
                </div>
              </div>

              {/* RIGHT SIDEBAR */}
              <div className="lg:col-span-1">
                <Card className="overflow-hidden border shadow-lg">
                  <div className="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b flex items-center justify-between">
                    <h2 className="text-xl font-semibold flex items-center gap-2 text-gray-800">
                      <Zap className="text-[#f2cf19]" size={20} />
                      Trending Topics
                    </h2>
                    <Button
                      variant="ghost"
                      size="sm"
                      className="flex items-center gap-1 hover:text-linkedin-primary"
                      onClick={handleRefreshTrending}
                    >
                      <RefreshCw size={16} className={`${isRefreshing ? "animate-spin" : ""}`} />
                      <span>Refresh</span>
                    </Button>
                  </div>

                  <div className="p-4">
                    <div className="mb-6">
                      <h3 className="text-md font-semibold text-center gap-2 text-gray-800 mb-6">
                        Explore trending topics and generate your post using filters.
                      </h3>
                      <div className="space-y-4">
                        {/* Persona / Style */}
                        <div className="mt-5">
                          <h5 className="text-sm font-bold mb-2">Customize Your Post</h5>
                          <label className="text-sm text-gray-500 block mb-1">Influencer Field</label>
                          <div className="relative">
                            <Select
                              value={selectedInfluencerField}
                              onValueChange={(val) => {
                                setSelectedInfluencerField(val);
                                setSelectedInfluencer("");
                              }}
                            >
                              <SelectTrigger className="h-8 text-sm w-full border rounded-lg transition focus:outline-linkedin-primary">
                                <SelectValue placeholder="Random" />
                              </SelectTrigger>
                              <SelectContent>
                                <SelectItem value="Random">Random</SelectItem>
                                <SelectItem value="Finance">Finance</SelectItem>
                                <SelectItem value="Human Resources">Human Resources</SelectItem>
                                <SelectItem value="Marketing">Marketing</SelectItem>
                                <SelectItem value="Technology">Technology</SelectItem>
                              </SelectContent>
                            </Select>
                          </div>

                          {selectedInfluencerField === "Finance" && (
                            <div className="mb-4 mt-4">
                              <label className="text-sm text-gray-500 block mb-1">Finance Influencer</label>
                              <Select value={selectedInfluencer} onValueChange={setSelectedInfluencer}>
                                <SelectTrigger className="h-8 text-sm w-full border rounded-lg transition focus:outline-linkedin-primary">
                                  <SelectValue placeholder="Random" />
                                </SelectTrigger>
                                <SelectContent>
                                  <SelectItem value="Random Finance">Random</SelectItem>
                                  <SelectItem value="Oana Labes">Oana Labes</SelectItem>
                                  <SelectItem value="Robert Kiyosaki">Robert Kiyosaki</SelectItem>
                                  <SelectItem value="Farnoosh Torabi">Farnoosh Torabi</SelectItem>
                                  <SelectItem value="Josh Aharonoff">Josh Aharonoff</SelectItem>
                                  <SelectItem value="Graham Stephan">Graham Stephan</SelectItem>
                                </SelectContent>
                              </Select>
                            </div>
                          )}

                          {selectedInfluencerField === "Human Resources" && (
                            <div className="mb-4 mt-4">
                              <label className="text-sm text-gray-500 block mb-1">HR Influencer</label>
                              <Select value={selectedInfluencer} onValueChange={setSelectedInfluencer}>
                                <SelectTrigger className="h-8 text-sm w-full border rounded-lg transition focus:outline-linkedin-primary">
                                  <SelectValue placeholder="Random" />
                                </SelectTrigger>
                                <SelectContent>
                                  <SelectItem value="Random Human Resources">Random</SelectItem>
                                  <SelectItem value="Ben Eubanks">Ben Eubanks</SelectItem>
                                  <SelectItem value="Brigette Hyacinth">Brigette Hyacinth</SelectItem>
                                  <SelectItem value="David Green">David Green</SelectItem>
                                  <SelectItem value="Hung Lee">Hung Lee</SelectItem>
                                  <SelectItem value="Jan Tegze">Jan Tegze</SelectItem>
                                  <SelectItem value="Suzanne Lucas">Suzanne Lucas</SelectItem>
                                </SelectContent>
                              </Select>
                            </div>
                          )}

                          {selectedInfluencerField === "Marketing" && (
                            <div className="mb-4 mt-4">
                              <label className="text-sm text-gray-500 block mb-1">Marketing Influencer</label>
                              <Select value={selectedInfluencer} onValueChange={setSelectedInfluencer}>
                                <SelectTrigger className="h-8 text-sm w-full border rounded-lg transition focus:outline-linkedin-primary">
                                  <SelectValue placeholder="Random" />
                                </SelectTrigger>
                                <SelectContent>
                                  <SelectItem value="Random Marketing">Random</SelectItem>
                                  <SelectItem value="Alex Liberman">Alex Liberman</SelectItem>
                                  <SelectItem value="Gary Vaynerchuk">Gary Vaynerchuk</SelectItem>
                                  <SelectItem value="Ann Handley">Ann Handley</SelectItem>
                                  <SelectItem value="Steven Bartlett">Steven Bartlett</SelectItem>
                                  <SelectItem value="Lara Acosta">Lara Acosta</SelectItem>
                                  <SelectItem value="Justin Welsh">Justin Welsh</SelectItem>
                                </SelectContent>
                              </Select>
                            </div>
                          )}

                          {selectedInfluencerField === "Technology" && (
                            <div className="mb-4 mt-4">
                              <label className="text-sm text-gray-500 block mb-1">Technology Influencer</label>
                              <Select value={selectedInfluencer} onValueChange={setSelectedInfluencer}>
                                <SelectTrigger className="h-8 text-sm w-full border rounded-lg transition focus:outline-linkedin-primary">
                                  <SelectValue placeholder="Any Tech Influencer" />
                                </SelectTrigger>
                                <SelectContent>
                                  <SelectItem value="Random Technology">Any Tech Influencer</SelectItem>
                                  <SelectItem value="Bernard Marr">Bernard Marr</SelectItem>
                                  <SelectItem value="Elena Verna">Elena Verna</SelectItem>
                                  <SelectItem value="Harry Stebbings">Harry Stebbings</SelectItem>
                                  <SelectItem value="Kieran Flanagan">Kieran Flanagan</SelectItem>
                                  <SelectItem value="Lenny Rachitsky">Lenny Rachitsky</SelectItem>
                                  <SelectItem value="Marcus Sheridan">Marcus Sheridan</SelectItem>
                                </SelectContent>
                              </Select>
                            </div>
                          )}

                          {/* Language */}
                          <label className="text-sm text-gray-500 block mb-1 mt-4">Post Languages</label>
                          <div className="relative">
                            <Select value={selectedLanguage} onValueChange={setSelectedLanguage}>
                              <SelectTrigger className="h-8 text-sm w-full border rounded-lg  transition focus:outline-linkedin-primary">
                                <SelectValue placeholder="Select Language" />
                              </SelectTrigger>
                              <SelectContent>
                                <SelectItem value="en">English</SelectItem>
                                <SelectItem value="fr">Français</SelectItem>
                                <SelectItem value="de">Deutsch</SelectItem>
                                <SelectItem value="es">Español</SelectItem>
                                <SelectItem value="it">Italiano</SelectItem>
                                <SelectItem value="pt">Português</SelectItem>
                                <SelectItem value="ru">Русский</SelectItem>
                                <SelectItem value="nl">Nederlands</SelectItem>
                                <SelectItem value="pl">Polski</SelectItem>
                                <SelectItem value="sv">Svenska</SelectItem>
                                <SelectItem value="tr">Türkçe</SelectItem>
                                <SelectItem value="uk">Українська</SelectItem>
                                <SelectItem value="cs">Čeština</SelectItem>
                                <SelectItem value="ro">Română</SelectItem>
                                <SelectItem value="el">Ελληνικά</SelectItem>
                                <SelectItem value="da">Dansk</SelectItem>
                                <SelectItem value="fi">Suomi</SelectItem>
                                <SelectItem value="no">Norsk</SelectItem>
                                <SelectItem value="hu">Magyar</SelectItem>
                                <SelectItem value="zh">中文</SelectItem>
                                <SelectItem value="ja">日本語</SelectItem>
                                <SelectItem value="ko">한국어</SelectItem>
                                <SelectItem value="ar">العربية</SelectItem>
                                <SelectItem value="hi">हिन्दी</SelectItem>
                                <SelectItem value="bn">বাংলা</SelectItem>
                                <SelectItem value="ur">اردو</SelectItem>
                                <SelectItem value="fa">فارسی</SelectItem>
                                <SelectItem value="id">Bahasa Indonesia</SelectItem>
                                <SelectItem value="ms">Bahasa Melayu</SelectItem>
                                <SelectItem value="th">ไทย</SelectItem>
                                <SelectItem value="vi">Tiếng Việt</SelectItem>
                                <SelectItem value="ta">தமிழ்</SelectItem>
                                <SelectItem value="te">తెలుగు</SelectItem>
                                <SelectItem value="ml">മലയാളം</SelectItem>
                                <SelectItem value="mr">मराठी</SelectItem>
                                <SelectItem value="pa">ਪੰਜਾਬੀ</SelectItem>
                                <SelectItem value="gu">ગુજરાતી</SelectItem>
                                <SelectItem value="kn">ಕನ್ನಡ</SelectItem>
                                <SelectItem value="my">မြန်မာ</SelectItem>
                                <SelectItem value="si">සිංහල</SelectItem>
                                <SelectItem value="ne">नेपाली</SelectItem>
                                <SelectItem value="he">עברית</SelectItem>
                                <SelectItem value="sw">Kiswahili</SelectItem>
                                <SelectItem value="yo">Yorùbá</SelectItem>
                                <SelectItem value="ig">Asụsụ Igbo</SelectItem>
                                <SelectItem value="am">አማርኛ</SelectItem>
                                <SelectItem value="zu">isiZulu</SelectItem>
                                <SelectItem value="ha">Hausa</SelectItem>
                                <SelectItem value="qu">Runa Simi (Quechua)</SelectItem>
                                <SelectItem value="gn">Avañe'ẽ (Guarani)</SelectItem>
                                <SelectItem value="mi">Te reo Māori</SelectItem>
                                <SelectItem value="tl">Tagalog</SelectItem>
                                <SelectItem value="jv">Basa Jawa</SelectItem>
                                <SelectItem value="su">Basa Sunda</SelectItem>
                                <SelectItem value="eo">Esperanto</SelectItem>
                              </SelectContent>
                            </Select>
                          </div>

                          {/* Post size */}
                          <div className="relative w-full">
                            <label className="text-sm text-gray-500 block mb-1 mt-4">Post Size</label>
                            <Select value={charLimit} onValueChange={setCharLimit}>
                              <SelectTrigger className="h-8 text-sm w-full border rounded-lg transition focus:outline-linkedin-primary">
                                <SelectValue placeholder="Post Size" />
                              </SelectTrigger>
                              <SelectContent>
                                <SelectItem value="500">500 Characters</SelectItem>
                                <SelectItem value="1000">1000 Characters</SelectItem>
                                <SelectItem value="1500">1500 Characters</SelectItem>
                                <SelectItem value="2000">2000 Characters</SelectItem>
                                <SelectItem value="2500">2500 Characters</SelectItem>
                                <SelectItem value="3000">3000 Characters</SelectItem>
                              </SelectContent>
                            </Select>
                          </div>
                        </div>

                        {/* NEWS FILTERS + SEARCH */}
                        <div className="mt-5">
                          <h5 className="text-sm font-bold mb-2">Filter Topics</h5>
                          <label className="text-sm text-gray-500 block mb-1">Genres</label>
                          <div className="relative">
                            <Select value={trendingField} onValueChange={setTrendingField}>
                              <SelectTrigger className="h-8 text-sm w-full border rounded-lg transition focus:outline-linkedin-primary">
                                <SelectValue placeholder="Select Category" />
                              </SelectTrigger>
                              <SelectContent>
                                <SelectItem value="all">All</SelectItem>
                                <SelectItem value="business">Business</SelectItem>
                                <SelectItem value="entertainment">Entertainment</SelectItem>
                                <SelectItem value="general">General</SelectItem>
                                <SelectItem value="health">Health</SelectItem>
                                <SelectItem value="science">Science</SelectItem>
                                <SelectItem value="sports">Sports</SelectItem>
                                <SelectItem value="technology">Technology</SelectItem>
                              </SelectContent>
                            </Select>
                          </div>

                          <label className="text-sm text-gray-500 block mb-1 mt-4">Country</label>
                          <div className="relative">
                            <Select value={trendingCountry} onValueChange={setTrendingCountry}>
                              <SelectTrigger className="h-8 text-sm w-full border rounded-lg transition focus:outline-linkedin-primary">
                                <SelectValue placeholder="Select Country" />
                              </SelectTrigger>
                              <SelectContent>
                                <SelectItem value="global">Global</SelectItem>
                                <SelectItem value="au">Australia</SelectItem>
                                <SelectItem value="br">Brazil</SelectItem>
                                <SelectItem value="ca">Canada</SelectItem>
                                <SelectItem value="cn">China</SelectItem>
                                <SelectItem value="eg">Egypt</SelectItem>
                                <SelectItem value="fr">France</SelectItem>
                                <SelectItem value="de">Germany</SelectItem>
                                <SelectItem value="gr">Greece</SelectItem>
                                <SelectItem value="hk">Hong Kong</SelectItem>
                                <SelectItem value="in">India</SelectItem>
                                <SelectItem value="ie">Ireland</SelectItem>
                                <SelectItem value="il">Israel</SelectItem>
                                <SelectItem value="it">Italy</SelectItem>
                                <SelectItem value="jp">Japan</SelectItem>
                                <SelectItem value="nl">Netherlands</SelectItem>
                                <SelectItem value="no">Norway</SelectItem>
                                <SelectItem value="pk">Pakistan</SelectItem>
                                <SelectItem value="pe">Peru</SelectItem>
                                <SelectItem value="ph">Philippines</SelectItem>
                                <SelectItem value="pt">Portugal</SelectItem>
                                <SelectItem value="ro">Romania</SelectItem>
                                <SelectItem value="ru">Russian Federation</SelectItem>
                                <SelectItem value="sg">Singapore</SelectItem>
                                <SelectItem value="es">Spain</SelectItem>
                                <SelectItem value="se">Sweden</SelectItem>
                                <SelectItem value="ch">Switzerland</SelectItem>
                                <SelectItem value="tw">Taiwan</SelectItem>
                                <SelectItem value="ua">Ukraine</SelectItem>
                                <SelectItem value="gb">United Kingdom</SelectItem>
                                <SelectItem value="us">United States</SelectItem>
                              </SelectContent>
                            </Select>
                          </div>

                          <label className="text-sm text-gray-500 block mb-1 mt-4">Search</label>
                          <form
                            onSubmit={(e) => {
                              e.preventDefault();
                              handleSearch();
                            }}
                            className="flex items-center gap-2"
                          >
                            <Input
                              value={searchQuery}
                              onChange={(e) => setSearchQuery(e.target.value)}
                              placeholder="Type to search…"
                              className="flex-1 p-2 border rounded-xl focus:outline-none focus:ring-linkedin-primary focus-visible:ring-linkedin-primary"
                            />
                            <button type="submit" className="bg-linkedin-primary rounded-full text-white p-3">
                              <FaSearch />
                            </button>
                          </form>
                        </div>
                      </div>
                    </div>

                    {/* TOPICS LIST */}
                    <div className="">
                      {isGenerating ? (
                        <div className="flex items-center justify-center py-6 gap-2">
                          <img src={Loader} alt="Loading…" className="h-15 w-12" />
                          <span className="text-gray-600">Post is generating…</span>
                        </div>
                      ) : !((trendingField && trendingCountry) || searchQuery.trim()) ? (
                        <div className="text-center text-gray-500 py-4">
                          Please select a topic and country, or enter a keyword to search.
                        </div>
                      ) : isFetchingInitial ? (
                        <p className="text-center py-4">Loading trending topics...</p>
                      ) : trendingTopics.length > 0 ? (
                        <div ref={trendingContainerRef} className="border rounded-md trending-topics bg-white divide-y">
                          {trendingTopics.map((topic, idx) => (
                            <TrendingTopic
                              key={idx}
                              title={topic.title}
                              source={topic.source}
                              url={topic.url}
                              onCreatePost={() => handleCreateFromTopic(topic.title, topic.description)}
                            />
                          ))}
                        </div>
                      ) : (
                        <div className="text-center py-4 text-gray-500">
                          {newsError
                            ? newsError
                            : hasSearched
                              ? "No trending topics found."
                              : "No news available yet for the current filters."}
                        </div>
                      )}
                    </div>
                  </div>
                </Card>
              </div>
            </div>
          </TabsContent>

          {/* SCHEDULED TAB */}
          <TabsContent value="scheduled" className="space-y-4 animate-fade-in">
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
              <div className="lg:col-span-2">
                <Card className="overflow-hidden border shadow-lg">
                  <div className="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
                    <h2 className="text-xl font-semibold flex items-center gap-2 text-gray-800">
                      <ListTodo className="text-linkedin-primary" size={20} />
                      Scheduled Posts
                    </h2>
                  </div>
                  <div className="p-6">
                    <div className="mb-4">
                      <p className="text-gray-600 italic">View all posts you’ve scheduled for publishing.</p>
                    </div>
                    {scheduledPosts.filter((p) => p.status === "pending").length > 0 ? (
                      <div className="space-y-2">
                        {scheduledPosts
                          .filter((p) => p.status === "pending")
                          .map((post) => (
                            <ScheduledPostItem
                              key={post.id}
                              post={post}
                              onCancel={handleDeletePublishedPost}
                              onEdit={handleEditScheduledPost}
                            />
                          ))}
                      </div>
                    ) : (
                      <div className="text-center py-8 border border-dashed rounded-lg">
                        <Calendar className="mx-auto h-12 w-12 text-gray-400 mb-2" />
                        <h3 className="text-lg font-medium text-gray-800 mb-1">No scheduled posts</h3>
                        <p className="text-gray-500 mb-4">You don't have any scheduled posts at the moment</p>
                        <Button
                          variant="outline"
                          className="border-linkedin-primary text-linkedin-primary hover:bg-[#0000ff] hover:text-white font-bold"
                          onClick={() => setActiveTab("create")}
                        >
                          Create a Post
                        </Button>
                      </div>
                    )}
                  </div>
                </Card>
              </div>

              <div className="lg:col-span-1">
                <Card className="overflow-hidden border shadow-lg">
                  <div className="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
                    <h2 className="text-xl font-semibold flex items-center gap-2 text-gray-800">
                      <CheckCircle className="text-[#d60000]" size={20} />
                      Published Posts
                    </h2>
                  </div>
                  <div className="p-6">
                    <div className="mb-4">
                      <p className="text-gray-600 italic">Posts that have been successfully published</p>
                    </div>
                    {(() => {
                      const published = scheduledPosts.filter((p) => p.status === "published");
                      if (published.length === 0) {
                        return (
                          <div className="text-center py-8 border border-dashed rounded-lg">
                            <FileCheck className="mx-auto h-12 w-12 text-gray-400 mb-2" />
                            <h3 className="text-lg font-medium text-gray-800 mb-1">No published posts</h3>
                            <p className="text-gray-500">Your published posts will appear here</p>
                          </div>
                        );
                      }
                      const lastPost = published[published.length - 1];
                      return (
                        <div className="space-y-2 max-h-[400px] overflow-y-auto pr-2">
                          <ScheduledPostItem
                            key={lastPost.id}
                            post={lastPost}
                            onCancel={handleDeletePublishedPost}
                            onEdit={handleEditScheduledPost}
                          />
                        </div>
                      );
                    })()}
                  </div>
                </Card>

                <div className="mt-6">
                  <Card className="p-5 border bg-gradient-to-br from-blue-50 to-indigo-50">
                    <h3 className="font-medium mb-3 text-gray-800">Analytics Overview</h3>
                    <div className="space-y-4">
                      <div className="bg-white p-3 rounded shadow-sm">
                        <div className="flex justify-between items-center mb-2">
                          <span className="text-sm text-gray-600">Total Posts</span>
                          <span className="text-lg font-medium">{scheduledPosts.length}</span>
                        </div>
                        <div className="h-2 bg-gray-100 rounded overflow-hidden">
                          <div
                            className="h-full bg-linkedin-primary rounded"
                            style={{ width: `${(scheduledPosts.length / 10) * 100}%` }}
                          ></div>
                        </div>
                      </div>
                      <div className="grid grid-cols-2 gap-2">
                        <div className="bg-white p-3 rounded shadow-sm">
                          <p className="text-sm text-gray-600 mb-1">Scheduled</p>
                          <p className="text-xl font-medium text-linkedin-primary">
                            {scheduledPosts.filter((p) => p.status === "pending").length}
                          </p>
                        </div>
                        <div className="bg-white p-3 rounded shadow-sm">
                          <p className="text-sm text-gray-600 mb-1">Published</p>
                          <p className="text-xl font-medium text-linkedin-primary">
                            {scheduledPosts.filter((p) => p.status === "published").length}
                          </p>
                        </div>
                      </div>
                    </div>
                  </Card>
                </div>
              </div>
            </div>
          </TabsContent>
        </Tabs>

        {/* Schedule dialog */}
        <Dialog open={isScheduleModalOpen} onOpenChange={setIsScheduleModalOpen}>
          <DialogOverlay className="fixed inset-0 bg-black/50" />
          <DialogContent className="sm:max-w-md fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded shadow-lg">
            <DialogHeader hideCloseButton>
              <DialogTitle className="flex items-center gap-2">
                <Calendar size={18} className="text-linkedin-primary" />
                Schedule Your Post
              </DialogTitle>
              <DialogDescription>Choose when you want your post to be published</DialogDescription>
            </DialogHeader>
            <div className="space-y-4 py-4">
              <div>
                <label className="text-sm font-medium text-gray-700 block mb-1">Date</label>
                <Input
                  type="date"
                  value={scheduleDate}
                  onChange={(e) => setScheduleDate(e.target.value)}
                  className="w-full"
                  min={new Date().toISOString().split("T")[0]}
                />
              </div>
              <div>
                <label className="text-sm font-medium text-gray-700 block mb-1">Time</label>
                <Input type="time" value={scheduleTime} onChange={(e) => setScheduleTime(e.target.value)} className="w-full" />
              </div>
            </div>
            <DialogFooter className="flex gap-2 sm:justify-end">
              <Button variant="outline" onClick={() => setIsScheduleModalOpen(false)}>
                Cancel
              </Button>
              <Button
                className="bg-linkedin-primary hover:bg-[#0000ffc4] text-white font-bold"
                onClick={handleSchedulePost}
                disabled={isPublishing}
              >
                {isPublishing ? "Scheduling..." : "Schedule Post"}
              </Button>
            </DialogFooter>
          </DialogContent>
        </Dialog>

        <ReportIssueModal isOpen={isReportModalOpen} onOpenChange={setIsReportModalOpen} />
      </div>
    </div>
  );
};

export default HomeThree;
