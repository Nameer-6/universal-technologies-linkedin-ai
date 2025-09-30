import React from "react";

interface Props {
  iso: string | null | undefined;
  label?: string;
  showTz?: boolean;
}

const LocalTime: React.FC<Props> = ({ iso, label, showTz }) => {
  if (!iso) return null;
  const dt = new Date(iso);
  const str = dt.toLocaleString(undefined, {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
    hour12: false,
  });
  const tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
  return (
    <span>
      {label ? <span>{label}: </span> : null}
      {str}
      {showTz ? ` (${tz})` : ""}
    </span>
  );
};

export default LocalTime;
