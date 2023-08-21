import React, { useState, useEffect } from 'react';
import Recipe from '../RecipeCard';
import './styles.css';
import RecipeInfo from '../RecipeInfo';


const RecipesContainer = ({ url, islist = true, refreshRecipes }) => {
  const [recipes, setRecipes] = useState([]);
  const [recipe_id, setRecipeId] = useState(null);
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
      if (url === '/personal_info') {
        if (islist) {
          setRecipes(data.list_recipes);
        } else {
          setRecipes(data.personal_recipes);
        }
      } else {
        setRecipes(data.recipes);
      }
    })
    .catch(error => {
      console.log('Error:', error);
    });
  };

  useEffect(() => {
    getRecipes();
  }, [url, islist]);

  return (
    <>
      <div className="content">
        <div className="recipe-container">
          {recipes.length === 0 ? (
            islist ? <div>No List Recipes...</div> : <div>No Personal Recipes...</div>
          ) : (
            recipes.map(recipe => (
              <Recipe key={recipe.id} recipe={recipe} getRecipes={getRecipes} setRecipeId={setRecipeId} islist={islist}  />
            ))
          )}
        </div>
      </div>
      {recipe_id && <RecipeInfo recipe_id={recipe_id} setRecipeId={setRecipeId} />}
    </>
  );
};

export default RecipesContainer;
