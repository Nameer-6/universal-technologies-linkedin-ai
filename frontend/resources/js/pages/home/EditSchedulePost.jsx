import React, { useState, useEffect } from "react";
import { useParams, useNavigate } from "react-router-dom";
import { toast, Toaster } from "react-hot-toast";
import axios from "axios";

// Assets (adjust paths as needed)
import profilePicPlaceholder from "../../assets/img/user-default.jpg";
import heartIcon from "../../assets/img/heart.svg";
import thumbIcon from "../../assets/img/thumb.svg";

// Components
import Schedule from "../../components/Schedule";

// Mantine RichTextEditor
import { RichTextEditor } from "@mantine/rte";

// MUI Date/Time Picker
import { DateTimePicker } from "@mui/x-date-pickers/DateTimePicker";
import { LocalizationProvider } from "@mui/x-date-pickers/LocalizationProvider";
import { AdapterDayjs } from "@mui/x-date-pickers/AdapterDayjs";
import TextField from "@mui/material/TextField";

// Day.js (with UTC and Timezone)
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";
dayjs.extend(utc);
dayjs.extend(timezone);
const KARACHI_TZ = "Asia/Karachi";

// MUI Icons
import { IconButton } from "@mui/material";
import CloseIcon from "@mui/icons-material/Close";

// Optional side image (if you want a background image)
import side_img from "../../../dist/img/side.jpg";

/**
 * Convert plain text (with newline characters) to minimal HTML for display
 * in the RichTextEditor. For example, we replace newlines with <br> tags.
 */
const plainTextToHtml = (text) => {
  if (!text) return "";
  // Check if text already has any HTML tags; if so, return as-is.
  if (/<[a-z][\s\S]*>/i.test(text)) return text;
  // Replace newlines with <br> tags
  return text.replace(/\n/g, "<br>");
};

/**
 * Convert editor HTML content into plain text while preserving spacing.
 * This conversion replaces end-of-paragraph tags (<p>, <br>) with newline characters,
 * then removes all other HTML tags.
 */
const htmlToPlainText = (html) => {
  if (!html) return "";
  // Replace </p> (end of paragraph) with a single newline for spacing
  let text = html.replace(/<\/p>/gi, "");
  // Replace <br> tags with one newline
  text = text.replace(/<br\s*\/?>/gi, "");
  // Remove any remaining HTML tags
  text = text.replace(/<[^>]+>/g, "");
  return text.trim();
};


const EditScheduledPost = () => {
  const { id } = useParams();
  const navigate = useNavigate();

  // postText is stored as HTML for use inside the editor
  const [postText, setPostText] = useState("");
  // Scheduled datetime (displayed in Karachi time)
  const [scheduledDatetime, setScheduledDatetime] = useState(dayjs().tz(KARACHI_TZ));
  const [loading, setLoading] = useState(false);

  // User profile (for preview header)
  const [userProfile, setUserProfile] = useState(null);

  // For optional media attachments (preview only)
  const [mediaData, setMediaData] = useState("");
  const [mediaType, setMediaType] = useState("");
  const [mediaFile, setMediaFile] = useState(null);
  const [editorContent, setEditorContent] = useState("");

  // For schedule posts list (if needed by Schedule component)
  const [scheduledPosts, setScheduledPosts] = useState([]);

  // Fetch user profile on mount
  useEffect(() => {
    axios
      .get("/api/profile", { withCredentials: true })
      .then((res) => {
        if (!res.data.error) {
          setUserProfile(res.data);
        }
      })
      .catch(() => {
        // Leave userProfile null on error
      });
  }, []);

  // Fetch the scheduled post to edit
  useEffect(() => {
    axios
      .get(`/api/scheduled-posts/${id}`, { withCredentials: true })
      .then((response) => {
        const post = response.data.scheduled_post;
        // If the stored post is plain text (without HTML tags),
        // convert it into minimal HTML for the editor.
        const content = plainTextToHtml(post.post_text || "");
        setPostText(content);

        // Convert the stored UTC datetime to Karachi time for display
        const karachiTime = dayjs(post.scheduled_datetime).tz(KARACHI_TZ);
        setScheduledDatetime(karachiTime);
      })
      .catch((error) => {
        if (error.response?.data) {
          toast.error(error.response.data.error || "Error fetching post");
        } else {
          toast.error("Error fetching post");
        }
      });
  }, [id]);

  // Fetch scheduled posts to pass into the Schedule component
  const fetchScheduledPosts = async () => {
    try {
      const apiBaseUrl = window.location.hostname !== 'localhost' 
        ? 'https://universal-technologies-linkedin-ai-production.up.railway.app' 
        : '';
      
      const res = await fetch(`${apiBaseUrl}/api/user-scheduled-posts`, { credentials: "include" });
      const data = await res.json();
      if (res.ok) setScheduledPosts(data.scheduled_posts || []);
    } catch (err) {
      toast.error("Error fetching scheduled posts");
    }
  };

  // Editor change: auto-convert markdown style * and ** to HTML tags
  const handleEditorChange = (value) => {
    let formattedValue = value;
    formattedValue = formattedValue.replace(/\*\*(.*?)\*\*/gs, "<strong>$1</strong>");
    formattedValue = formattedValue.replace(/\*(.*?)\*/gs, "<em>$1</em>");
    setPostText(formattedValue);
  };

  // Handle media attachments for preview
  const handleMediaChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    // Revoke previous URL if exists
    if (mediaData) {
      URL.revokeObjectURL(mediaData);
    }
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
    let type = file.type;
    if (type.startsWith("video/")) type = "video";
    else if (type === "application/pdf") type = "pdf";
    else if (type.startsWith("image/")) type = "image";

    setMediaType(type);
    setMediaData(objectUrl);
    setMediaFile(file);
  };

  // Determines if the editor has any non-empty content.
  const isEditorEmpty = () =>
    !postText || postText.trim() === "" || postText.trim() === "<p><br></p>";

  // When saving, convert the HTML content to plain text to avoid storing formatting tags.
  const handleSave = () => {
    setLoading(true);
    // Convert the Karachi time back to UTC format
    const utcTime = scheduledDatetime.tz("UTC").format();

    // Convert HTML editor content to plain text (preserving spacing)
    const plainText = htmlToPlainText(postText);

    // Prepare form data; use plain text instead of HTML for post_text.
    const formData = new FormData();
    formData.append("id", id);
    formData.append("post_text", plainText);
    formData.append("scheduled_datetime", utcTime);
    if (mediaFile) {
      formData.append("media", mediaFile);
      formData.append("media_type", mediaType);
    }

    axios
      .post("/api/scheduled-posts/update", formData, { withCredentials: true })
      .then((response) => {
        toast.success(response.data.message || "Post updated");
        navigate("/"); // Redirect to main page or posts listing
      })
      .catch((error) => {
        if (error.response?.data) {
          toast.error(error.response.data.error || "Error updating post");
        } else {
          toast.error("Error updating post");
        }
      })
      .finally(() => {
        setLoading(false);
      });
  };

  return (
    <div>
      <Toaster />

      {/* Optional side image column */}
      <div className="d-flex">
        {/* Left Column: Editor and Scheduling */}
        <div className="form-area position-relative w-100">
          <div className="inner pb-100 clearfix">
            <div className="container form-content pera-content">
              <h2>Edit Scheduled Post</h2>
              <div className="row mt-4">
                {/* Post Editor */}
                <div className="col-md-6">
                  <div className="mb-3">
                    <label className="form-label">Post Content:</label>
                    <RichTextEditor
                      value={postText}
                      onChange={handleEditorChange}
                      placeholder="Your AI-generated or custom text will appear here..."
                      controls={[
                        ["h1", "h2", "h3", "blockquote", "code"],
                        ["bold", "italic", "underline", "strike"],
                        ["link"],
                        ["unorderedList", "orderedList", "checklist"],
                        ["alignLeft", "alignCenter", "alignRight"],
                      ]}
                    />
                  </div>

                  {/* Media Input */}
                  <div className="mt-3 mb-3">
                    <label htmlFor="mediaUpload" className="form-label">
                      Add/Edit Media:
                    </label>
                    <input
                      id="mediaUpload"
                      type="file"
                      accept="image/*, video/*, application/pdf"
                      onChange={handleMediaChange}
                      className="form-control"
                    />
                  </div>

                  {/* Scheduled Date & Time Picker */}
                  <div className="mb-2">
                    <label className="form-label">Scheduled Date &amp; Time (Karachi):</label>
                    <LocalizationProvider dateAdapter={AdapterDayjs}>
                      <DateTimePicker
                        label="Schedule Date & Time"
                        value={scheduledDatetime}
                        onChange={(newValue) => {
                          if (newValue) {
                            setScheduledDatetime(dayjs(newValue).tz(KARACHI_TZ));
                          }
                        }}
                        timeSteps={{ minutes: 1 }}
                        renderInput={(params) => <TextField {...params} className="form-control" />}
                      />
                    </LocalizationProvider>
                  </div>
                  <button
                    className={`post hover:text-[#fff]`}
                    onClick={handleSave}
                    disabled={loading || isEditorEmpty()}
                  >
                    {loading ? "Saving..." : "Save Changes"}
                  </button>
                </div>

                {/* Post Preview */}
                <div className="col-md-6 mt-md-0 mt-4">
                  <div className="bg-white border border-gray-200 rounded p-4 shadow-sm">
                    {/* User Info */}
                    <div className="d-flex align-items-center">
                      <img
                        src={userProfile?.profilePic || profilePicPlaceholder}
                        alt="User Avatar"
                        style={{ width: 40, height: 40, borderRadius: "50%", objectFit: "cover" }}
                      />
                      <div className="ms-3">
                        <div className="fw-bold text-dark">
                          {userProfile?.name || "John Doe"}
                        </div>
                        <div className="text-muted" style={{ fontSize: "0.9rem" }}>
                          {userProfile?.headline || ""}
                        </div>
                        <div className="d-flex align-items-center text-muted gap-1 mt-1" style={{ fontSize: "0.8rem" }}>
                          <span>Now</span>
                          <span>•</span>
                          <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" style={{ width: 16, height: 16 }}>
                            <path d="M8 1a7 7 0 107 7 7 7 0 00-7-7zM3 8a5 5 0 011-3l.55.55A1.5 1.5 0 015 6.62v1.07a.75.75 0 00.22.53l.56.56a.75.75 0 00.53.22H7v.69a.75.75 0 00.22.53l.56.56a.75.75 0 01.22.53V13a5 5 0 01-5-5z" />
                          </svg>
                        </div>
                      </div>
                    </div>

                    {/* Post Content Preview */}
                    <div
                      className="mt-3"
                      style={{ fontSize: "0.9rem", color: "#333" }}
                      dangerouslySetInnerHTML={{
                        __html: isEditorEmpty()
                          ? `<p class="mb-1">Your post content will appear here...</p>`
                          : postText,
                      }}
                    />

                    {/* Media Preview */}
                    {mediaData && (
                      <div className="mt-3 position-relative">
                        <IconButton
                          onClick={() => {
                            URL.revokeObjectURL(mediaData);
                            setMediaData("");
                            setMediaType("");
                            setMediaFile(null);
                          }}
                          style={{
                            position: "absolute",
                            top: 0,
                            right: 0,
                            background: "rgba(255,255,255,0.8)",
                          }}
                          size="small"
                          aria-label="remove attachment"
                        >
                          <CloseIcon fontSize="small" />
                        </IconButton>
                        {mediaType === "video" ? (
                          <video controls src={mediaData} style={{ maxWidth: "100%", maxHeight: 400, display: "block", margin: "0 auto" }} />
                        ) : mediaType === "pdf" ? (
                          <embed src={mediaData} type="application/pdf" style={{ width: "100%", height: 500 }} />
                        ) : (
                          <img src={mediaData} alt="Selected" style={{ maxWidth: "100%", maxHeight: 400, display: "block", margin: "0 auto" }} />
                        )}
                      </div>
                    )}

                    {/* Social Stats (Static Example) */}
                    <div className="d-flex align-items-center justify-content-between text-muted mt-3" style={{ fontSize: "0.8rem" }}>
                      <div className="d-flex align-items-center">
                        <img src={heartIcon} alt="Heart" style={{ width: 18, height: 18, marginLeft: "-3px" }} />
                        <img src={thumbIcon} alt="Thumb" style={{ width: 18, height: 18, marginLeft: "-7px" }} />
                        <span className="ms-1">20k</span>
                      </div>
                      <div className="d-flex align-items-center gap-2">
                        <span>1k comments</span>
                        <span>•</span>
                        <span>60 reposts</span>
                      </div>
                    </div>

                    <hr className="mt-3 mb-2" />

                    {/* Action Buttons (Static Example) */}
                    <div className="d-flex justify-content-around">
                      <span className="d-flex align-items-center gap-1">
                        <i className="fa-regular fa-thumbs-up"></i> Like
                      </span>
                      <span className="d-flex align-items-center gap-1">
                        <i className="fa-regular fa-comment"></i> Comment
                      </span>
                      <span className="d-flex align-items-center gap-1">
                        <i className="fa-solid fa-retweet"></i> Repost
                      </span>
                      <span className="d-flex align-items-center gap-1">
                        <i className="fa-regular fa-paper-plane"></i> Send
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {/* Right Column: Schedule Component (Full Height) */}
          </div>
        </div>
        <Schedule scheduledPosts={scheduledPosts} refreshPosts={fetchScheduledPosts} />

      </div>
    </div>
  );
};

export default EditScheduledPost;
