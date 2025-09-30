import React, { useState } from 'react';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter, DialogOverlay } from './ui/dialog';
import { Button } from './ui/button';
import { Input } from './ui/input';
import { toast } from 'react-hot-toast';
import { Camera, Lightbulb, Upload, X } from 'lucide-react';

interface ReportIssueModalProps {
  isOpen: boolean;
  onOpenChange: (open: boolean) => void;
}

export const ReportIssueModal: React.FC<ReportIssueModalProps> = ({ isOpen, onOpenChange }) => {
  const [reportName, setReportName] = useState('');
  const [reportEmail, setReportEmail] = useState('');
  const [reportMessage, setReportMessage] = useState('');
  const [reportImage, setReportImage] = useState<File | null>(null);
  const [reportImageUrl, setReportImageUrl] = useState('');
  const [isSubmittingReport, setIsSubmittingReport] = useState(false);
const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!reportName.trim() || !reportEmail.trim() || !reportMessage.trim()) {
        toast.error("Please fill in all required fields.");
        return;
    }
    if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(reportEmail)) {
        toast.error("Please enter a valid email address.");
        return;
    }
    
    setIsSubmittingReport(true);
    try {
        const formData = new FormData();
        formData.append("name", reportName.trim());
        formData.append("email", reportEmail.trim());
        formData.append("message", reportMessage.trim());
        
        if (reportImage instanceof File) {
            formData.append("image", reportImage);
            console.log("Attaching image:", {
                name: reportImage.name,
                size: reportImage.size,
                type: reportImage.type
            });
        }

        const res = await fetch("/api/report-issue", {
            method: "POST",
            credentials: "include",
            body: formData,
        });
        
        if (!res.ok) {
            const errorData = await res.json();
            throw new Error(errorData.error || "Failed to submit report");
        }

        const data = await res.json();
        toast.success(data.message || "Thank you for your feedback!");
        onOpenChange(false);
        resetForm();
    } catch (err) {
        console.error("Submission error:", err);
        toast.error(err.message || "Failed to submit report. Please try again.");
    } finally {
        setIsSubmittingReport(false);
    }
};

const resetForm = () => {
    setReportName("");
    setReportEmail("");
    setReportMessage("");
    if (reportImageUrl) {
        URL.revokeObjectURL(reportImageUrl);
    }
    setReportImage(null);
    setReportImageUrl("");
};

  const handleImageUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) {
      console.log("No file selected");
      return;
    }
    if (!["image/jpeg", "image/png", "image/jpg"].includes(file.type)) {
      toast.error("Only JPEG and PNG files are allowed.");
      return;
    }
    if (file.size > 5 * 1024 * 1024) {
      toast.error("File size must be less than 5MB.");
      return;
    }
    if (reportImageUrl) {
      URL.revokeObjectURL(reportImageUrl);
    }
    const objectUrl = URL.createObjectURL(file);
    console.log("Setting reportImage:", file); // Debug log
    setReportImage(file);
    setReportImageUrl(objectUrl);
  };

  const removeImage = () => {
    if (reportImageUrl) {
      URL.revokeObjectURL(reportImageUrl);
    }
    console.log("Setting reportImage: null"); // Debug log
    setReportImage(null);
    setReportImageUrl("");
  };

  return (
    <Dialog open={isOpen} onOpenChange={onOpenChange}>
      <DialogOverlay className="fixed inset-0 bg-black/40 z-40" />
      <DialogContent className="sm:max-w-md fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-background p-6 rounded-xl shadow-xl z-50 border max-h-[90vh] overflow-y-auto">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2 text-[#0000ff]">
            <Lightbulb className="text-[#0000ff]" size={18} />
            Report an Issue
          </DialogTitle>
          <DialogDescription className="text-sm text-muted-foreground">
            Help us improve by reporting a problem or feedback.
          </DialogDescription>
        </DialogHeader>
        <form className="space-y-4 pt-4" onSubmit={handleSubmit}>
          <Input
            placeholder="Your Name"
            value={reportName}
            onChange={e => setReportName(e.target.value)}
            className="mb-2 focus-visible:ring-2 focus-visible:ring-[#0000ff] focus-visible:ring-offset-2 "
            required
          />
          <Input
            type="email"
            placeholder="Your Email"
            value={reportEmail}
            onChange={e => setReportEmail(e.target.value)}
            className="mb-2 focus-visible:ring-2 focus-visible:ring-[#0000ff] focus-visible:ring-offset-2 "
            required
          />
          <textarea
            rows={4}
            placeholder="Describe the issue or feedback..."
            value={reportMessage}
            onChange={e => setReportMessage(e.target.value)}
            className="w-full border border-input rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-[#0000ff] focus:ring-offset-2 text-foreground bg-background resize-none"
            required
          />
          <div className="space-y-3">
            <label className="block text-sm font-medium text-foreground">
              Add Screenshot <span className="text-muted-foreground">(optional)</span>
            </label>
            {!reportImageUrl ? (
              <div className="group">
                <label
                  htmlFor="report-image-upload"
                  className="flex flex-col items-center justify-center w-full h-36 rounded-xl border-2 border-dashed border-border hover:border-primary/60 hover:bg-accent/30 cursor-pointer transition-all duration-300 group-hover:scale-[1.02]"
                >
                  <div className="flex flex-col items-center gap-3">
                    <div className="relative">
                      <div className="p-4 rounded-full bg-gray-50 transition-all duration-300 group-hover:scale-110">
                        <Upload className="w-8 h-8 text-black-500" />
                      </div>
                      <div className="absolute -top-1 -right-1 p-1 rounded-full border-2 border-background">
                        <Camera className="w-3 h-3 text-muted-foreground" />
                      </div>
                    </div>
                    <div className="text-center space-y-1">
                      <p className="text-sm font-medium text-foreground group-hover:text-primary transition-colors">
                        Upload screenshot
                      </p>
                      <p className="text-xs text-muted-foreground">
                        Click to browse or drag and drop
                      </p>
                      <p className="text-xs text-muted-foreground bg-accent/50 px-2 py-1 rounded">
                        JPEG, PNG • Max 5MB
                      </p>
                    </div>
                  </div>
                  <input
                    id="report-image-upload"
                    type="file"
                    accept=".jpeg,.jpg,.png"
                    className="hidden"
                    onChange={handleImageUpload}
                  />
                </label>
              </div>
            ) : (
              <div className="relative group">
                <div className="relative w-full h-36 rounded-xl border-2 border-border bg-accent/20 overflow-hidden">
                  <img
                    src={reportImageUrl}
                    alt="Screenshot Preview"
                    className="w-full h-full object-cover transition-transform group-hover:scale-105"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                  <button
                    type="button"
                    onClick={removeImage}
                    className="absolute top-2 right-2 p-2 bg-destructive/90 hover:bg-destructive text-destructive-foreground rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110"
                    aria-label="Remove image"
                  >
                    <X className="w-4 h-4" />
                  </button>
                  <div className="absolute bottom-2 left-2 px-2 py-1 bg-background/90 rounded text-xs font-medium text-foreground opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    {reportImage?.name}
                  </div>
                </div>
                <label
                  htmlFor="report-image-upload-replace"
                  className="absolute inset-0 flex items-center justify-center bg-black/0 hover:bg-black/20 transition-colors cursor-pointer rounded-xl"
                >
                  <div className="opacity-0 group-hover:opacity-100 transition-opacity bg-background/90 px-3 py-1.5 rounded-lg shadow-lg">
                    <span className="text-sm font-medium text-foreground">Replace</span>
                  </div>
                  <input
                    id="report-image-upload-replace"
                    type="file"
                    accept=".jpeg,.jpg,.png"
                    className="hidden"
                    onChange={handleImageUpload}
                  />
                </label>
              </div>
            )}
          </div>
          <DialogFooter className="flex gap-2 justify-end pt-4">
            <Button variant="outline" onClick={() => onOpenChange(false)} type="button">
              Cancel
            </Button>
            <Button
              type="submit"
              className="bg-[#0000ff] text-primary-foreground font-medium hover:bg-[#0000ffc4] transition-colors"
              disabled={isSubmittingReport}
            >
              {isSubmittingReport ? "Submitting..." : "Submit Report"}
            </Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>
  );
};