import React, { useState, useEffect } from 'react';
import Recipe from '../RecipeCard';
import './styles.css';
import RecipeInfo from '../RecipeInfo';

const RecipesContainer = ({ url, islist = true, searchRecipe }) => {
  const [recipes, setRecipes] = useState([]);
  const [recipe_id, setRecipeId] = useState(null);
  const token = localStorage.getItem('token');
  const [headerContent, setHeaderContent] = useState(null);

  const fetchData = () => { 
    if (searchRecipe && searchRecipe.length > 0) {
      setHeaderContent(<h1>Search Results</h1>);
      setRecipes(searchRecipe);
    } else {
      fetch(`http://127.0.0.1:8000/api${url}`, {
        method: 'GET',
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
      })
      .then(response => response.json())
      .then(data => {
        let content;
        if (url === '/personal_info') {
          if (islist) {
            content = <h1></h1>;
            setRecipes(data.list_recipes);
          } else {
            content = <h1></h1>;
            setRecipes(data.personal_recipes);
          }
        } else {
          content = <h1>Followers Recipes</h1>;
          setRecipes(data.recipes);
        }
        setHeaderContent(content);
      })
      .catch(error => {
        console.log('Error:', error);
      });
    }
  };

  useEffect(() => {
    fetchData();
  }, [url, islist, searchRecipe, token]);

  return (
    <>  
      <div className="content">
        {headerContent}
        <div className="recipe-container">
          {recipes.length === 0 ? (
            <div> No Recipes...</div> 
          ) : (
            recipes.map(recipe => (
              <Recipe key={recipe.id} recipe={recipe} getRecipes={() => fetchData()} setRecipeId={setRecipeId} islist={islist}  />
            ))
          )}
        </div>
      </div>
      {recipe_id && <RecipeInfo recipe_id={recipe_id} setRecipeId={setRecipeId} />}
    </>
  );
};

export default RecipesContainer;
