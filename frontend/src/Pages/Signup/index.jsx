import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './styles.css'

const SignUp = () => {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [checkpassword, setCheckPassword] = useState('');
  const navigate = useNavigate();

  const handlePasswordMatch = () => {
    return password === checkpassword;
  };

  const handleSignUp = async () => {
    try {

      if (!handlePasswordMatch()) {
        console.log('Passwords do not match');
        return;
      }
      const response = await fetch('http://127.0.0.1:8000/api/signup', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ name, email, password }),
      });

      const data = await response.json();

      if (data.message === 'User created successfully') {
        console.log('Token:', data.token);
        localStorage.setItem("token" , data.token)
        navigate('/main'); 

      } else {
        console.log('Signup failed:', data.message);
      }
    } catch (error) {
      console.log('Error:', error);
    }
  };

  return (
    <div className='center'>
      <div className='signup-form'>
        <h1>Sign Up</h1>
        <div className ='input-container'>
          <label>Name:</label>
          <input type="text" value={name} onChange={(e) => setName(e.target.value)} />
        </div>
        <div className ='input-container'>
          <label>Email:</label>
          <input type="text" value={email} onChange={(e) => setEmail(e.target.value)} />
        </div>
        <div className ='input-container'>
          <label>Password:</label>
          <input type="password" value={password} onChange={(e) => setPassword(e.target.value)} />
        </div>
        <div className ='input-container'>
          <label>Confirm Password:</label>
          <input type="password" value={checkpassword} onChange={(e) => setCheckPassword(e.target.value)} />
        </div>
        <div>
          <button onClick={handleSignUp}>Sign Up</button>
        </div>
      </div>
    </div>
  );
};

export default SignUp;
