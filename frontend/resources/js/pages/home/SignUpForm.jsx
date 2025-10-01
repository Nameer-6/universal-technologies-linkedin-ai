import React, { useState, useRef } from 'react';
import {
  Card,
  CardContent,
  Typography,
  Stack,
  Input,
  Button,
  Divider
} from '@mui/joy';
import 'bootstrap/dist/css/bootstrap.min.css';
import ReCAPTCHA from 'react-google-recaptcha';

const PaymentSummary = ({ title, description, amount }) => (
  <div className="card mb-3">
    <div className="card-body">
      <div className="d-flex justify-content-between">
        <div className="d-flex flex-row align-items-center">
          <div>
            <i
              className="fa fa-money"
              style={{ fontSize: '25px', color: '#28a745' }}
              aria-hidden="true"
            ></i>
          </div>
          <div className="ms-3">
            <h5>{title}</h5>
            <p className="small mb-0">{description}</p>
          </div>
        </div>
        <div className="d-flex flex-row align-items-center">
          <div>
            <h5 className="mb-0">{amount}</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
);

const SignupForm = () => {
  const [name, setName]         = useState('');
  const [email, setEmail]       = useState('');
  const [password, setPassword] = useState('');
  const [error, setError]       = useState('');
  const [loading, setLoading]   = useState(false);
  const [redirecting, setRedirecting] = useState(false);
  const [recaptchaToken, setRecaptchaToken] = useState('');
  const recaptchaRef = useRef(null);

  const handlePayNow = async (e) => {
    e.preventDefault();

    if (!name || !email || !password) {
      setError('Please fill in all fields.');
      return;
    }

    // reCAPTCHA temporarily disabled for deployment
    // if (!recaptchaToken) {
    //   setError('Please complete the reCAPTCHA.');
    //   return;
    // }

    setLoading(true);
    setError('');

    try {
      const response = await fetch('/api/signup', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, email, password })
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.error || 'Failed to create checkout session.');
      }

      if (data.url) {
        setRedirecting(true);
        window.location.href = data.url;
      } else {
        throw new Error('No checkout URL returned from server.');
      }
    } catch (err) {
      setError(err.message);
      setLoading(false);
    }

    if (recaptchaRef.current) {
      recaptchaRef.current.reset();
      setRecaptchaToken('');
    }
  };

  return (
    <section
      className="vh-100 d-flex flex-column justify-content-center align-items-center"
      style={{ backgroundColor: '#eee' }}
    >
      <div className="container d-flex flex-column justify-content-center align-items-center">
        <div className="card w-100 h-100">
          <div className="card-body p-4">
            <div className="row">
              {/* Left column: Payment summary */}
              <div className="col-lg-7" style={{ order: '2' }}>
                <h5 className="mb-3">
                  Pay with <span style={{ color: '#D60000' }}>Stripe</span>
                </h5>
                <hr />
                <PaymentSummary
                  title="One Time Payment"
                  description="One time payment"
                  amount="$10"
                />
                <PaymentSummary
                  title="Recurring Payment"
                  description="Monthly subscription"
                  amount="$1"
                />
                <Divider sx={{ my: 2 }} />
                <Stack direction="row" justifyContent="space-between" mb={2}>
                  <Typography>Total</Typography>
                  <Typography>$11.00</Typography>
                </Stack>
              </div>

              {/* Right column: User info + Pay Now */}
              <div className="col-lg-5">
                <Card
                  variant="outlined"
                  sx={{
                    borderRadius: '16px',
                    maxWidth: 400,
                    height: '100%',
                    mx: 'auto',
                    p: 2
                  }}
                >
                  <CardContent>
                    {error && <div className="alert alert-danger">{error}</div>}
                    <Stack
                      direction="row"
                      justifyContent="space-between"
                      alignItems="center"
                      mb={2}
                    >
                      <Typography
                        level="h5"
                        sx={{
                          fontWeight: 'bold',
                          color: '#32383e',
                          fontSize: '25px'
                        }}
                      >
                        User <span style={{ color: '#D60000' }}>Information</span>
                      </Typography>
                    </Stack>

                    <form onSubmit={handlePayNow}>
                      <Stack spacing={2} mb={2}>
                        <Input
                          placeholder="Full Name"
                          size="lg"
                          sx={{
                            backgroundColor: 'white',
                            color: 'black',
                            borderRadius: '8px'
                          }}
                          value={name}
                          onChange={e => setName(e.target.value)}
                        />
                        <Input
                          placeholder="Email"
                          size="lg"
                          sx={{
                            backgroundColor: 'white',
                            color: 'black',
                            borderRadius: '8px'
                          }}
                          value={email}
                          onChange={e => setEmail(e.target.value)}
                        />
                        <Input
                          placeholder="Password"
                          type="password"
                          size="lg"
                          sx={{
                            backgroundColor: 'white',
                            color: 'black',
                            borderRadius: '8px'
                          }}
                          value={password}
                          onChange={e => setPassword(e.target.value)}
                        />
                      </Stack>

                      {/* reCAPTCHA widget - temporarily disabled for deployment */}
                      {/* <div className="mb-3">
                        <ReCAPTCHA
                          sitekey="6LfVcQErAAAAAIim_YPPu2Vu2jSAr-VxjAgTEcon"
                          onChange={token => setRecaptchaToken(token)}
                          ref={recaptchaRef}
                        />
                      </div> */}

                      <Button
                        variant="solid"
                        color="primary"
                        size="lg"
                        fullWidth
                        sx={{ textTransform: 'none' }}
                        type="submit"
                        disabled={loading || redirecting}
                      >
                        {redirecting
                          ? 'Redirecting to Stripe...'
                          : loading
                          ? 'Processing...'
                          : 'Pay Now'}
                      </Button>
                    </form>
                  </CardContent>
                </Card>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default SignupForm;
