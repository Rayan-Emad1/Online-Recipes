import React from "react";
import {BrowserRouter, Route, Routes} from 'react-router-dom'

import Signin from "./Pages/Signin";
import Signup from "./Pages/Signup";
import Main from "./Pages/Main";
import Profile from "./Pages/Profile";




function App() {
  return (
    <BrowserRouter>
        <Routes>
          <Route index element={<Signin />} />
          <Route path="/signup" element={<Signup />} />
          <Route path="/main" element={<Main />} />
          <Route path="/profile" element={<Profile />} />
        </Routes>
  </BrowserRouter>
  );
}

export default App;
