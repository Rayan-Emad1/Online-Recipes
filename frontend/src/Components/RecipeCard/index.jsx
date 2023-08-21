import React, { useState,useEffect } from 'react';
import './styles.css';

const Recipe = ({ recipe, getRecipes, setRecipeId, islist }) => {
  const token = localStorage.getItem('token');
  

  const Togglelike = (recipeId) => {
  
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

  const ToggleList = (recipeId) => {
    fetch(`http://127.0.0.1:8000/api/add_recipe`, {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ recipe_id: recipeId }),
    })
    .then(response => response.json())
    .then(data => {
      console.log('Toggle list response:', data);
      getRecipes();
    })
    .catch(error => {
      console.log('Error:', error);
    });
  };

  return (
    <div className="recipe-card">
      <img className='recipe-image' src={`https://picsum.photos/200`} alt={`recipe by ${recipe.user_name}`} onClick={()=>setRecipeId(recipe.id)}/>
      <h3>{recipe.name}</h3>
      <div className='like-container'>
        
        <button className={`like-button-recipe`} onClick={() => ToggleList(recipe.id)}>
        {recipe.is_in_list || islist ? 'Remove Cart' : 'Add Cart'}
        </button>
        {!islist && <button className={`like-button-recipe`} onClick={() => Togglelike(recipe.id)}>
          {recipe.is_liked_by_user ? 'Unlike' : 'Like'}
        </button> }
        

      </div>
    </div>
  );
};

export default Recipe;
