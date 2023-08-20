import React, { useState, useEffect } from 'react';
import './styles.css';

function RecipeInfo({ recipe_id, setRecipeId }) {
  const token = localStorage.getItem('token');
  const [recipeInfo, setRecipeInfo] = useState({});
  const [commentText, setCommentText] = useState('');

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

  const addComment = () => {
    fetch('http://127.0.0.1:8000/api/comment', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        recipe_id: recipe_id,
        comment_text: commentText,
      }),
    })
      .then(response => response.json())
      .then(data => {
        getRecipesInfo();
        setCommentText('');
      })
      .catch(error => {
        console.log('Error adding comment:', error);
      });
  }

  useEffect(() => {
    getRecipesInfo()
  }, [recipe_id]);

  if (!recipeInfo.name) {
    return null;
  }

  return (
    <div className='center-overlap' >
      <div className='recipe-info-card'>
        <div className='left-sec'>
          <img className="info-card-img" src="https://picsum.photos/200" alt="" />
        </div>
        <div className='right-sec'>
          <h1 className='close-info' onClick={ () => setRecipeId(null)} >X</h1>
          <h1>{recipeInfo.name}</h1>
          <h4>Created By: {recipeInfo.user_name}</h4>
          <p>Cuisine: {recipeInfo.cuisine}</p>
          <p>Ingredients: {recipeInfo.ingredients}</p>

          <div className='statistics'>
            <div className='likes-by-users'>
              <h3>{recipeInfo.likes} Likes by :</h3>
              <ul>
                {recipeInfo.likes_by_users.map((user, index) => (
                  <li key={index}>{user.user_name}</li>
                ))}
              </ul>
            </div>
            <div className='comments'>
              <h3>Comments:</h3>
              <ul>
                {recipeInfo.comments.map((comment, index) => (
                  <li key={index}>
                    <strong>{comment.user_name}:</strong> {comment.comment_text}
                  </li>
                ))}
              </ul>

            </div>
          </div>

          <div className='add-comment-container'>
                <input
                  type='text'
                  placeholder='Add a comment...'
                  value={commentText}
                  onChange={event => setCommentText(event.target.value)}
                />
                <button className='add-comment-button' onClick={addComment}>
                  Add Comment
                </button>
          </div>
        </div>
      </div>
    </div>
  );
}

export default RecipeInfo;
