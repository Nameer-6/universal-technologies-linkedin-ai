import html2canvas from "html2canvas";
import jsPDF from "jspdf";
import React, { useEffect, useRef, useState } from "react";
import { toast, Toaster } from "react-hot-toast";
import logo_loader from "../../assets/img/logo-glitch.gif";
// We’ll use Lucide React icons for our carousel controls and other actions:
import {
    ChevronLeft,
    ChevronRight,
    FileDown,
    Plus,
    Trash2
} from "lucide-react";
import Schedule from "../../components/Schedule";

/**
 * Type definition (optional in pure JS; remove or convert to JSDoc if needed):
 * 
 * interface Slide {
 *   title: string;
 *   content: string;
 *   type?: "cover" | "content";
 * }
 */

const PdfGenerator = () => {
  // ------------------ State ------------------
  const [formData, setFormData] = useState({ topics: "", country: "" });
  const [trendingTopics, setTrendingTopics] = useState([]);
  const [isFetchingTrendingTopics, setIsFetchingTrendingTopics] = useState(false);
  const [selectedTopic, setSelectedTopic] = useState(null);
  const [slides, setSlides] = useState([]);
  const [isGeneratingSlides, setIsGeneratingSlides] = useState(false);
  const [showAllTrending, setShowAllTrending] = useState(false);
  const [scheduledPosts, setScheduledPosts] = useState([]);
  // We’re not editing slides inline (like a text editor) in this snippet,
  // but if desired you can keep editingSlideIndex logic here:
  const [editingSlideIndex, setEditingSlideIndex] = useState(null);

  // PDF & user profile
  const [userProfile, setUserProfile] = useState(null);
  const [profileLoading, setProfileLoading] = useState(true);

  // Carousel: track which slide is currently displayed
  const [currentSlide, setCurrentSlide] = useState(0);

  // ------------------ Refs ------------------
  const topicsListRef = useRef(null);
  const carouselRef = useRef(null);
  const pdfContainerRef = useRef(null);

  // ------------------ Effects ------------------
  // 1. Fetch user profile
  useEffect(() => {
    const fetchProfile = async () => {
      try {
        const token = localStorage.getItem('auth_token');
        if (!token) {
          setProfileLoading(false);
          return;
        }
        
        const apiBaseUrl = window.location.hostname !== 'localhost' 
        ? 'https://universal-technologies-linkedin-ai-production.up.railway.app' 
        : '';
      
      const res = await fetch(`${apiBaseUrl}/api/profile`, { 
          credentials: "include",
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          }
        });
        const data = await res.json();
        if (!data.error) {
          setUserProfile(data);
        }
      } catch (err) {
        console.error("Error fetching profile:", err);
      } finally {
        setProfileLoading(false);
      }
    };
    fetchProfile();
  }, []);

  // 2. Fetch scheduled posts
  const fetchScheduledPosts = async () => {
    try {
      const res = await fetch(`${apiBaseUrl}/api/user-scheduled-posts`, {
        credentials: "include",
      });
      const data = await res.json();
      if (res.ok) {
        setScheduledPosts(data.scheduled_posts || []);
      } else {
        toast.error(data.error || "Error fetching scheduled posts");
      }
    } catch (err) {
      console.error("Error fetching scheduled posts:", err);
      toast.error("Error fetching scheduled posts");
    }
  };

  useEffect(() => {
    fetchScheduledPosts();
  }, []);

  // If user has selected a “topics” and “country,” fetch trending topics:
  useEffect(() => {
    if (formData.topics && formData.country) {
      fetchTrendingTopics();
    }
  }, [formData.topics, formData.country]);

  // For autoscrolling if user clicks "Show More"
  useEffect(() => {
    if (showAllTrending && topicsListRef.current) {
      topicsListRef.current.scrollTo({
        top: topicsListRef.current.scrollHeight,
        behavior: "smooth",
      });
    }
  }, [showAllTrending]);

  // ------------------ Methods ------------------
  // 1. Handle <select> dropdown changes
  const handleSelectChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
    // Reset things on new selection
    setTrendingTopics([]);
    setSelectedTopic(null);
    setSlides([]);
    setShowAllTrending(false);
    setCurrentSlide(0);
  };

  // 2. Fetch trending topics
  const fetchTrendingTopics = async () => {
    const { topics, country } = formData;
    setIsFetchingTrendingTopics(true);
    setSelectedTopic(null);
    setSlides([]);
    try {
      const res = await fetch(`${apiBaseUrl}/api/generate-news`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ category: topics, country }),
      });
      const data = await res.json();
      if (res.ok) {
        setTrendingTopics(data.trending_topics || []);
      } else {
        toast.error(data.error || "Error fetching trending topics");
      }
    } catch (err) {
      console.error("Error fetching trending topics:", err);
      toast.error("Error fetching trending topics");
    } finally {
      setIsFetchingTrendingTopics(false);
    }
  };

  // 3. On user clicks a trending topic
  const handleTopicClick = async (topicTitle) => {
    const topic = trendingTopics.find((t) => t.title === topicTitle);
    if (!topic) return;
    setSelectedTopic(topic);
    setSlides([]);
    setIsGeneratingSlides(true);
    setCurrentSlide(0);

    try {
      // This endpoint returns AI-generated slides for the chosen topic
      const res = await fetch(`${apiBaseUrl}/api/generate-ppt`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          title: topic.title,
          description: topic.description,
          source: topic.source,
          publishedAt: topic.publishedAt,
        }),
      });
      const data = await res.json();

      if (res.ok && data.slides) {
        setSlides(data.slides);
      } else {
        toast.error(data.error || "Error generating PDF slides");
      }
    } catch (err) {
      console.error("Error generating PDF slides:", err);
      toast.error("Error generating PDF slides");
    } finally {
      setIsGeneratingSlides(false);
    }
  };

  // 4. Slide operations
  const handleRemoveSlide = (index) => {
    setSlides((prev) => prev.filter((_, i) => i !== index));
    if (editingSlideIndex === index) {
      setEditingSlideIndex(null);
    } else if (editingSlideIndex > index) {
      setEditingSlideIndex(editingSlideIndex - 1);
    }
    // If we remove the last slide being shown, go one step back
    if (currentSlide === slides.length - 1 && currentSlide > 0) {
      setCurrentSlide(currentSlide - 1);
    }
  };

  const handleTitleChange = (index, newTitle) => {
    setSlides((prev) =>
      prev.map((slide, i) => (i === index ? { ...slide, title: newTitle } : slide))
    );
  };

  const handleContentChange = (index, newContent) => {
    setSlides((prev) =>
      prev.map((slide, i) => (i === index ? { ...slide, content: newContent } : slide))
    );
  };

  // 5. PDF Download
  const downloadPDF = async () => {
    if (slides.length === 0) {
      toast.error("No slides to download!");
      return;
    }
    try {
      const pdf = new jsPDF("p", "pt", "a4");
      const pageWidth = pdf.internal.pageSize.getWidth();
      const pageHeight = pdf.internal.pageSize.getHeight();
      const slideElements = pdfContainerRef.current.querySelectorAll(".pdf-slide");

      for (let i = 0; i < slideElements.length; i++) {
        const slideEl = slideElements[i];
        // Render each slide to a canvas
        const canvas = await html2canvas(slideEl, {
          scale: 2,
          logging: true,
          useCORS: true,
        });
        const imgData = canvas.toDataURL("image/png");
        const ratio = canvas.height / canvas.width;
        const imgWidth = pageWidth;
        const imgHeight = pageWidth * ratio;

        pdf.addImage(imgData, "PNG", 0, 0, imgWidth, imgHeight);
        pdf.setFontSize(12);
        pdf.text(
          `Slide ${i + 1} of ${slideElements.length}`,
          pageWidth - 100,
          pageHeight - 20
        );

        if (i < slideElements.length - 1) {
          pdf.addPage();
        }
      }

      pdf.save("slides.pdf");
      toast.success("PDF downloaded successfully!");
    } catch (error) {
      console.error("Error generating PDF:", error);
      toast.error("Failed to generate PDF");
    }
  };

  // 6. Carousel navigation
  const navigateSlide = (direction) => {
    if (direction === "next" && currentSlide < slides.length - 1) {
      setCurrentSlide(currentSlide + 1);
    } else if (direction === "prev" && currentSlide > 0) {
      setCurrentSlide(currentSlide - 1);
    }
  };

  // Because we removed the separate "SlideCard" editing logic,
  // you can still keep these placeholders if you want inline editing:
  const handleEditSlide = (index) => {
    // Toggle edit mode for a specific slide
    setEditingSlideIndex((prev) => (prev === index ? null : index));
  };
  const handleSaveSlide = (index) => {
    // End edit mode
    setEditingSlideIndex(null);
  };

  // ------------------ Render ------------------
  if (profileLoading) {
    return (
      <div
        className="d-flex justify-content-center align-items-center"
        style={{ height: "100vh" }}
      >
        Loading...
      </div>
    );
  }

  return (
    <div>
      <Toaster />
      <div className="d-flex">
        {/* LEFT SIDE: PDF Generator area */}
        <div className="form-area position-relative w-100">
          <div className="inner pb-100 clearfix">
            <div className="container form-content pera-content">
              {/* Section Title */}
              <div className="step-inner-content">
                <div className="main-title">
                  <h2 className="title mb-2">
                    Generate PDF Slides <br />
                    <span className="divider"></span>
                    <span style={{ color: "#D60000" }}>with AI</span>
                  </h2>
                </div>
                <p>
                  Select your news category and country to fetch trending topics,
                  then generate AI-powered PDF slides.
                </p>

                {/* Category Dropdown */}
                <div className="select-field pt-4">
                  <h3>Select Emerging News Category:</h3>
                  <select
                    name="topics"
                    value={formData.topics}
                    onChange={handleSelectChange}
                    className="form-select mt-4"
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
                </div>

                {/* Country Dropdown */}
                <div className="select-field pt-4">
                  <h3>Select Country:</h3>
                  <select
                    name="country"
                    value={formData.country}
                    onChange={handleSelectChange}
                    className="form-select mt-4"
                  >
                    <option value="">Select Country</option>
                    <option value="au">Australia</option>
                    <option value="br">Brazil</option>
                    <option value="ca">Canada</option>
                    <option value="cn">China</option>
                    <option value="eg">Egypt</option>
                    <option value="fr">France</option>
                    <option value="de">Germany</option>
                    <option value="gr">Greece</option>
                    <option value="hk">Hong Kong</option>
                    <option value="in">India</option>
                    <option value="ie">Ireland</option>
                    <option value="il">Israel</option>
                    <option value="it">Italy</option>
                    <option value="jp">Japan</option>
                    <option value="nl">Netherlands</option>
                    <option value="no">Norway</option>
                    <option value="pk">Pakistan</option>
                    <option value="pe">Peru</option>
                    <option value="ph">Philippines</option>
                    <option value="pt">Portugal</option>
                    <option value="ro">Romania</option>
                    <option value="ru">Russian Federation</option>
                    <option value="sg">Singapore</option>
                    <option value="es">Spain</option>
                    <option value="se">Sweden</option>
                    <option value="ch">Switzerland</option>
                    <option value="tw">Taiwan</option>
                    <option value="ua">Ukraine</option>
                    <option value="gb">United Kingdom</option>
                    <option value="us">United States</option>
                  </select>
                </div>

                {/* Trending Topics List */}
                {formData.topics && formData.country && (
                  <div
                    className="step-box animated fadeInDown"
                    style={{ marginTop: 20 }}
                  >
                    <h3>
                      Select Emerging News in{" "}
                      {formData.topics.charAt(0).toUpperCase() +
                        formData.topics.slice(1)}{" "}
                      ({formData.country.toUpperCase()}):
                    </h3>
                    <ul
                      className="trending_topics_list overflow-y-auto"
                      ref={topicsListRef}
                      style={{ maxHeight: "250px" }}
                    >
                      {isFetchingTrendingTopics ? (
                        <div className="d-flex align-items-center gap-2">
                          <img
                            src={logo_loader}
                            alt="Loading"
                            style={{ width: "45px" }}
                          />
                          <p className="blinking my-2">
                            Searching Trending Topics...
                          </p>
                        </div>
                      ) : trendingTopics.length > 0 ? (
                        trendingTopics
                          .slice(
                            0,
                            showAllTrending
                              ? trendingTopics.length
                              : 5
                          )
                          .map((topic, index) => (
                            <li key={index}>
                              <label
                                className="fetched"
                                onClick={() =>
                                  handleTopicClick(topic.title)
                                }
                                style={{ cursor: "pointer" }}
                              >
                                {topic.title}
                                <div
                                  className="d-flex justify-content-between text-muted mt-1"
                                  style={{ fontSize: "12px" }}
                                >
                                  <span>{topic.source}</span>
                                </div>
                              </label>
                            </li>
                          ))
                      ) : (
                        <p className="text-center">
                          No trending topics found. Please try again later.
                        </p>
                      )}
                    </ul>
                    {!showAllTrending && trendingTopics.length > 5 && (
                      <div className="flex">
                        <button
                          type="button"
                          onClick={() => setShowAllTrending(true)}
                          className="btn m-auto text-white"
                          style={{
                            background: "#0000ff",
                            fontWeight: "600",
                          }}
                        >
                          Show More
                        </button>
                      </div>
                    )}
                  </div>
                )}

                {/* If a topic was selected, show heading & loading indicator */}
                {selectedTopic && (
                  <div
                    className="mt-4"
                    style={{
                      textAlign: "center",
                      marginBottom: "20px",
                    }}
                  >
                    <h3>Selected Topic: {selectedTopic.title}</h3>
                    {isGeneratingSlides && (
                      <div>
                        <img
                          src={logo_loader}
                          alt="Generating"
                          style={{ width: "40px" }}
                        />
                        <p>Generating PDF slides with AI...</p>
                      </div>
                    )}
                  </div>
                )}

                {/* Carousel with Slides (only if slides.length > 0) */}
                {slides.length > 0 && (
                  <>
                    <h4 className="text-center" style={{ marginBottom: "10px" }}>
                      PDF Slides Preview
                    </h4>

                    {/* Container for carousel UI */}
                    <div className="mb-8">
                      <div className="relative bg-gray-50 rounded-lg shadow-sm border border-gray-200 p-6">
                        {/* Navigation Buttons */}
                        <div className="flex justify-between items-center mb-4">
                          <div className="flex space-x-2">
                            <div className="p-2">
                              {/* Example "Hamburger" lines (decoration) */}
                              <div className="w-4 h-4 flex flex-col gap-1">
                                <div className="h-[1px] w-full bg-gray-500"></div>
                                <div className="h-[1px] w-full bg-gray-500"></div>
                                <div className="h-[1px] w-full bg-gray-500"></div>
                              </div>
                            </div>
                          </div>
                          <div className="flex space-x-2">
                            <button
                              onClick={() => navigateSlide("prev")}
                              disabled={currentSlide === 0}
                              className="disabled:opacity-50 p-2"
                            >
                              <ChevronLeft className="h-4 w-4" />
                            </button>
                            <button
                              onClick={() => navigateSlide("next")}
                              disabled={currentSlide === slides.length - 1}
                              className="disabled:opacity-50 p-2"
                            >
                              <ChevronRight className="h-4 w-4" />
                            </button>
                            <button
                              onClick={() => handleRemoveSlide(currentSlide)}
                              className="text-red-500 p-2"
                            >
                              <Trash2 className="h-4 w-4" />
                            </button>
                            {/* Example: add a new empty slide (optional) */}
                            <button
                              onClick={() => {
                                const newSlide = {
                                  title: "New Slide",
                                  content: "Add your content here.",
                                };
                                setSlides([...slides, newSlide]);
                                setCurrentSlide(slides.length);
                                toast.success("New slide added!");
                              }}
                              className="text-green-500 p-2"
                            >
                              <Plus className="h-4 w-4" />
                            </button>
                            <button
                              onClick={downloadPDF}
                              className="text-blue-500 p-2"
                            >
                              <FileDown className="h-4 w-4" />
                            </button>
                          </div>
                        </div>

                        {/* Slides Container */}
                        <div className="flex overflow-hidden" ref={carouselRef}>
                          <div
                            className="flex transition-transform duration-300 ease-in-out w-full"
                            style={{
                              transform: `translateX(-${currentSlide * 100}%)`,
                            }}
                          >
                            {slides.map((slide, index) => (
                              <div
                                key={index}
                                className="min-w-full bg-[#f1f6f3] p-8"
                                style={{
                                  display: "flex",
                                  flexDirection: "column",
                                  justifyContent: "space-between",
                                  minHeight: "500px",
                                }}
                              >
                                {/* Slide Content */}
                                <div className="flex-1 flex flex-col justify-center items-center">
                                  <div className="w-full max-w-xl mx-auto">
                                    {slide.type === "cover" ? (
                                      <>
                                        <h1 className="text-4xl font-bold text-slate-700 mb-6">
                                          {slide.title}
                                        </h1>
                                        <p className="text-lg text-slate-600">
                                          {slide.content}
                                        </p>
                                      </>
                                    ) : (
                                      <>
                                        <div className="inline-block rounded-full bg-teal-500 text-white px-3 py-1 text-sm mb-4">
                                          {index}
                                        </div>
                                        <h2 className="text-3xl font-bold text-slate-700 mb-4">
                                          {slide.title}
                                        </h2>
                                        <p className="text-lg text-slate-600">
                                          {slide.content}
                                        </p>
                                      </>
                                    )}
                                  </div>
                                </div>

                                {/* Slide Footer (profile info + optional button) */}
                                <div className="mt-8 flex items-center">
                                  <div className="flex items-center gap-2">
                                    {/* If you have userProfile, show its initial or "A" fallback */}
                                    <div className="h-10 w-10 rounded-full bg-gray-800 flex items-center justify-center text-white">
                                      {userProfile?.name
                                        ? userProfile.name.charAt(0).toUpperCase()
                                        : "A"}
                                    </div>
                                    <div>
                                      <div className="font-semibold text-sm">
                                        {userProfile?.name || "User"}
                                      </div>
                                      <div className="text-xs text-gray-500">
                                        {userProfile?.role ||
                                          userProfile?.headline ||
                                          "Professional"}
                                      </div>
                                    </div>
                                  </div>

                                  {slide.type === "cover" && (
                                    <div className="ml-auto">
                                      <button className="bg-teal-500 text-white rounded-full px-4 py-2 text-sm">
                                        Swipe &gt;
                                      </button>
                                    </div>
                                  )}
                                </div>
                              </div>
                            ))}
                          </div>
                        </div>

                        {/* Slide Indicators */}
                        <div className="mt-4 flex justify-center">
                          {slides.map((_, index) => (
                            <button
                              key={index}
                              className={`h-1.5 mx-1 rounded-full transition-all ${
                                index === currentSlide
                                  ? "w-6 bg-blue-500"
                                  : "w-2.5 bg-gray-300"
                              }`}
                              onClick={() => setCurrentSlide(index)}
                            />
                          ))}
                        </div>
                      </div>
                    </div>

                  </>
                )}
              </div>
            </div>
          </div>
        </div>

        {/* RIGHT SIDE: Schedule Component */}
        <Schedule scheduledPosts={scheduledPosts} refreshPosts={fetchScheduledPosts} />
      </div>

      {/* Hidden container for PDF generation (captured by html2canvas -> jsPDF) */}
      <div
        ref={pdfContainerRef}
        style={{
          position: "absolute",
          top: "-9999px",
          left: "-9999px",
          width: "595px",
        }}
      >
        {slides.map((slide, index) => (
          <div
            key={index}
            className="pdf-slide"
            style={{
              width: "595px",
              minHeight: "842px",
              boxSizing: "border-box",
              padding: "20px",
              marginBottom: "20px",
              position: "relative",
              background: "#fff",
              border: "1px solid #eee",
            }}
          >
            {/* Use similar layout for the PDF version */}
            <div
              className="min-w-full p-8"
              style={{
                display: "flex",
                flexDirection: "column",
                justifyContent: "space-between",
                minHeight: "700px",
              }}
            >
              {/* Slide Content */}
              <div className="flex-1 flex flex-col justify-center items-center">
                <div className="w-full max-w-xl mx-auto">
                  {slide.type === "cover" ? (
                    <>
                      <h1 className="text-4xl font-bold text-slate-700 mb-6">
                        {slide.title}
                      </h1>
                      <p className="text-lg text-slate-600">
                        {slide.content}
                      </p>
                    </>
                  ) : (
                    <>
                      <div className="inline-block rounded-full bg-teal-500 text-white px-3 py-1 text-sm mb-4">
                        {index}
                      </div>
                      <h2 className="text-3xl font-bold text-slate-700 mb-4">
                        {slide.title}
                      </h2>
                      <p className="text-lg text-slate-600">
                        {slide.content}
                      </p>
                    </>
                  )}
                </div>
              </div>

              {/* Slide Footer */}
              <div className="mt-8 flex items-center">
                <div className="flex items-center gap-2">
                  <div className="h-10 w-10 rounded-full bg-gray-800 flex items-center justify-center text-white">
                    {userProfile?.name
                      ? userProfile.name.charAt(0).toUpperCase()
                      : "A"}
                  </div>
                  <div>
                    <div className="font-semibold text-sm">
                      {userProfile?.name || "User"}
                    </div>
                    <div className="text-xs text-gray-500">
                      {userProfile?.role || userProfile?.headline || "Professional"}
                    </div>
                  </div>
                </div>
                {slide.type === "cover" && (
                  <div className="ml-auto">
                    <button className="bg-teal-500 text-white rounded-full px-4 py-2 text-sm">
                      Swipe &gt;
                    </button>
                  </div>
                )}
              </div>
            </div>

            {/* Example “Slide X of Y” footer text (optional) */}
            <div
              style={{
                position: "absolute",
                bottom: "5px",
                right: "10px",
                fontSize: "10px",
                color: "#999",
              }}
            >
              Slide {index + 1} of {slides.length}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default PdfGenerator;
