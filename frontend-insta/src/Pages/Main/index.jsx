import React, { useState, useEffect } from 'react';
import Header from '../../Components/Header';
import PostContainer from '../../Components/PostsContainer'
import './styles.css'

const Main = () => {

  return (
    <div className="main-container">
      <Header />
      <PostContainer url = {'/posts'} />
    </div>
  );
};

export default Main;
