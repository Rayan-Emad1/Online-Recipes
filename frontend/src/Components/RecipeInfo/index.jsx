import React, { useState, useEffect } from 'react';
import './styles.css';

function RecipeInfo({ recipe_id, setRecipeId }) {
  const token = localStorage.getItem('token');
  const [recipeInfo, setRecipeInfo] = useState({});

  const getRecipesInfo = () => {
    fetch('http://127.0.0.1:8000/api/recipe_info', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ recipe_id }),
    })
      .then(response => response.json())
      .then(data => {
        setRecipeInfo(data.recipe_info);
      })
      .catch(error => {
        console.log('Error:', error);
      });
  }

  useEffect(() => {
    getRecipesInfo()
  }, [recipe_id]);

  if (!recipeInfo.name) {
    return null;
  }

  return (
    <div className='center-overlap' onClick={() => setRecipeId(null)}>
      <div className='recipe-info-card'>
        <div className='left-sec'>
          <img className="info-card-img" src="https://picsum.photos/200" alt="" />
        </div>
        <div className='right-sec'>
          <h1>{recipeInfo.name}</h1>
          <h4>Created By: {recipeInfo.user_name}</h4>
          <p>Cuisine: {recipeInfo.cuisine}</p>
          <p>Ingredients: {recipeInfo.ingredients}</p>
          
          <div className='statistics'>
            <ul>
              <h3>{recipeInfo.likes} Likes by :</h3>
                {recipeInfo.likes_by_users.map((user, index) => (
                  <li key={index}>{user.user_name}</li>
                ))}
            </ul>
            <ul>
              <h3>Comments:</h3>
                {recipeInfo.comments.map((comment, index) => (
                  <li key={index}>
                    <strong>{comment.user_name}:</strong> {comment.comment_text}
                  </li>
                ))}
            </ul>
          </div>
        </div>
      </div>
    </div>
  );
}

export default RecipeInfo;
