import React, { useState, useEffect } from 'react';
import Header from '../../Components/Header';
import RecipesContainer from '../../Components/RecipesContainer'
import './styles.css'

const Main = () => {
  const [searchRecipe,setSearchRecipe] = useState([])

  return (
    <div className="main-container">
      <Header setSearchRecipe={setSearchRecipe} />
      <RecipesContainer url = {'/recipes'} searchRecipe={searchRecipe}  islist={false} />
    </div>
  );
};

export default Main;
