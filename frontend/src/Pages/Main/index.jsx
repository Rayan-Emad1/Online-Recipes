import React, { useState, useEffect } from 'react';
import Header from '../../Components/HeaderProfile';
import RecipesContainer from '../../Components/RecipesContainer'
import './styles.css'

const Main = () => {

  return (
    <div className="main-container">
      <Header />
      <RecipesContainer url = {'/recipes'} />
    </div>
  );
};

export default Main;
