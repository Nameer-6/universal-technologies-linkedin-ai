import React from "react";
import { Link, useNavigate } from "react-router-dom";
import { toast } from "react-hot-toast";

const HeaderFive = ({ userProfile, handleLogout }) => {
  const navigate = useNavigate();

  return (
    <nav className="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
      <div className="container">
        <Link className="navbar-brand" to="/linkedin-ai">
          LinkedIn AI
        </Link>
        <button
          className="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarHeader"
          aria-controls="navbarHeader"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span className="navbar-toggler-icon"></span>
        </button>
        <div className="collapse navbar-collapse" id="navbarHeader">
          <ul className="navbar-nav ms-auto mb-2 mb-lg-0">
            {userProfile && (
              <>
                <li className="nav-item me-3">
                  <Link className="nav-link" to="/billing-details">
                    Billing Details
                  </Link>
                </li>
                <li className="nav-item">
                  <button
                    className="btn btn-danger"
                    onClick={handleLogout}
                  >
                    Logout
                  </button>
                </li>
              </>
            )}
          </ul>
        </div>
      </div>
    </nav>
  );
};

export default HeaderFive;
