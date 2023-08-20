import React, { useState } from 'react';
import './styles.css';

const Recipe = ({ recipe, getRecipes }) => {
  const token = localStorage.getItem('token');
  

  const likeRecipe = (recipeId) => {
  
    fetch('http://127.0.0.1:8000/api/like', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ recipe_id: recipeId }),
    })
    .then(response => response.json())
    .then(data => {
      console.log('Like recipe response:', data);
      getRecipes();
    })
    .catch(error => {
      console.log('Error:', error);
    });
  };
  

  return (
    <div className="recipe-card">
      <img className='recipe-image' src={`https://picsum.photos/${recipe.id}`} alt={`recipe by ${recipe.user_name}`} />
      <h3>{recipe.name}</h3>
      <div className='like-container'>
        
        <button className={`like-button-recipe`} onClick={() => likeRecipe(recipe.id)}>
          Add to Cart
        </button>

        <button className={`like-button-recipe`} onClick={() => likeRecipe(recipe.id)}>
          {recipe.is_liked_by_user? 'Unlike' : 'Like'}
        </button>

      </div>
    </div>
  );
};

export default Recipe;
