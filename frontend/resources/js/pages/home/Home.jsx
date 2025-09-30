import 'bootstrap/dist/css/bootstrap.min.css';
import React from 'react';
import { Col, Container, Nav, Navbar, Row } from 'react-bootstrap';

// Example assets (replace with your own)
import img from './../../assets/img/about/service-hero1.jpg';
// Logo removed as requested

function Home() {
  return (
    // Parent container to hold the circle behind the navbar
    <div style={{ position: 'relative' }}>
      {/* ========== NAVBAR ========== */}
      <Navbar
        expand="lg"
        className="py-3 shadow-sm"
        style={{
          position: 'relative',
          zIndex: 2, // Ensures navbar is in front of the circle
        }}
      >
        <Container>
          {/* Logo removed as requested */}
          <Navbar.Brand href="#home">
            Universal Technologies
          </Navbar.Brand>
          <Navbar.Toggle aria-controls="basic-navbar-nav" />
          <Navbar.Collapse
            id="basic-navbar-nav"
            className="justify-content-end"
          >
            <Nav>
              <Nav.Link href="#home" className="mx-2">
                HOME
              </Nav.Link>
              <Nav.Link href="#about" className="mx-2">
                ABOUT US
              </Nav.Link>
              <Nav.Link href="#services" className="mx-2">
                SERVICES
              </Nav.Link>
              <Nav.Link href="#portfolio" className="mx-2">
                PORTFOLIO
              </Nav.Link>
              <Nav.Link href="#contact" className="mx-2">
                CONTACT US
              </Nav.Link>
            </Nav>
          </Navbar.Collapse>
        </Container>
      </Navbar>

      {/* Circle behind the navbar (top-left) */}
      <div
        style={{
          position: 'absolute',
          top: 0,
          left: '0',
          width: '500px',
          height: '500px',
          borderRadius: '50%',
          overflow: 'hidden',
          boxShadow: '0 0 15px rgba(0,0,0,0.1)',
          zIndex: 1, // Behind the navbar
        }}
      >
        <img
          src={img}
          alt="Circle 1"
          style={{
            width: '100%',
            height: '100%',
            objectFit: 'cover',
          }}
        />
      </div>

      {/* ========== HERO SECTION ========== */}
      <section
        style={{
          position: 'relative',
          overflow: 'hidden',
          minHeight: '100vh', // Fill full screen height
          backgroundColor: '#fff',
          display: 'flex',
          alignItems: 'center', // Vertically center content
        }}
      >
        <Container>
          <Row className="align-items-center" style={{ height: '100%' }}>
            <Col lg={6} className="mb-5 mb-lg-0">
              <h1 className="display-3 fw-bold mb-4" style={{ color: '#000' }}>
                Digital <span style={{ color: '#E42D2D' }}>Solution</span>
              </h1>
              <p className="lead" style={{ maxWidth: '500px' }}>
                At Universal Technologies we offer a comprehensive suite of digital
                marketing and creative services designed to evolve your brand,
                engage your audience, and drive measurable results.
              </p>
            </Col>
            <Col lg={6}>
              {/* Main circular image on the right */}
              <div
                style={{
                  width: '320px',
                  height: '320px',
                  borderRadius: '50%',
                  overflow: 'hidden',
                  margin: '0 auto',
                  boxShadow: '0 0 20px rgba(0,0,0,0.1)',
                }}
              >
                <img
                  src={img}
                  alt="Right Hero"
                  style={{
                    width: '100%',
                    height: '100%',
                    objectFit: 'cover',
                  }}
                />
              </div>
            </Col>
          </Row>
        </Container>

        {/* Another circular image (right side) */}
        <div
          style={{
            position: 'absolute',
            top: '120px',
            right: '8%',
            width: '150px',
            height: '150px',
            borderRadius: '50%',
            overflow: 'hidden',
            boxShadow: '0 0 15px rgba(0,0,0,0.1)',
          }}
        >
          <img
            src="https://via.placeholder.com/300x300?text=Right+Circle+2"
            alt="Circle 2"
            style={{
              width: '100%',
              height: '100%',
              objectFit: 'cover',
            }}
          />
        </div>

        {/* Smaller circular image (bottom-left) */}
        <div
          style={{
            position: 'absolute',
            bottom: '40px',
            left: '10%',
            width: '130px',
            height: '130px',
            borderRadius: '50%',
            overflow: 'hidden',
            boxShadow: '0 0 15px rgba(0,0,0,0.1)',
          }}
        >
          <img
            src="https://via.placeholder.com/300x300?text=Left+Circle+3"
            alt="Circle 3"
            style={{
              width: '100%',
              height: '100%',
              objectFit: 'cover',
            }}
          />
        </div>

        {/* Approach • Creativity • Experienced (bottom-right) */}
        <div
          style={{
            position: 'absolute',
            bottom: '20px',
            right: '5%',
            color: '#E42D2D',
            fontWeight: 'bold',
          }}
        >
          Approach • Creativity • Experienced
        </div>
      </section>
    </div>
  );
}

export default Home;
