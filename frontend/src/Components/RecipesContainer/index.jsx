import React, { useState, useEffect } from 'react';
import Recipe from '../Post';
import './styles.css';


const RecipesContainer = ({ url }) => {
  const [recipes,setRecipes] = useState([])
  const token = localStorage.getItem('token');


const getRecipes = () => {
  fetch(`http://127.0.0.1:8000/api${url}`, {
    method: 'GET',
    headers: {
      Authorization: `Bearer ${token}`,
      'Content-Type': 'application/json',
    },
  })
  .then(response => response.json())
  .then(data => {
    setRecipes(data.recipes);
  })
  .catch(error => {
    console.log('Error:', error);
  });
};

  useEffect(() => {
    getRecipes();
  }, [url]);

  return (
    <div className="content">
      <div className="recipe-container">
        {recipes.map(recipe => (
          <Recipe key={recipe.id} recipe={recipe}  getRecipes={getRecipes} />
        ))}
      </div>
    </div>
  );
};

export default RecipesContainer;
