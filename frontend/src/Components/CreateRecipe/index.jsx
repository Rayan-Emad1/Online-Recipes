import React, { useState } from 'react';
import './styles.css';

const CreateRecipe = () => {
  const [nameInput, setNameInput] = useState('');
  const [cuisineInput, setCuisineInput] = useState('');
  const [ingredientsInput, setIngredientsInput] = useState('');
  const [fileInput, setFileInput] = useState(null);
  const [showForm, setShowForm] = useState(false);

  const toggleForm = () => {
    setShowForm(!showForm);
  };

  const handleNameChange = (event) => {
    setNameInput(event.target.value);
  };

  const handleCuisineChange = (event) => {
    setCuisineInput(event.target.value);
  };

  const handleIngredientsChange = (event) => {
    setIngredientsInput(event.target.value);
  };

  const handleImageChange = (event) => {
    setFileInput(event.target.files[0]);
  };

  const handleSubmit = async () => {
    const formData = new FormData();
    formData.append('name', nameInput);
    formData.append('cuisine', cuisineInput);
    formData.append('ingredients', ingredientsInput);
    formData.append('image', fileInput);

    try {
      const token = localStorage.getItem('token');
      const response = await fetch('http://127.0.0.1:8000/api/create_recipe', {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${token}`,
        },
        body: formData,
      });

      const data = await response.json();
      console.log(data);
    } catch (error) {
      console.error('Error sending data:', error);
    }

    setNameInput('');
    setCuisineInput('');
    setIngredientsInput('');
    setFileInput(null);
    toggleForm();
  };

  return (
    <div className="create-recipe">
      <button className="toggle-form-btn" onClick={toggleForm}>
        Create Recipe
      </button>
      {showForm && (
        <div className="transitionDiv">
          <input className="name-input" placeholder="Recipe Name" value={nameInput} onChange={handleNameChange} />
          <input className="cuisine-input" placeholder="Cuisine" value={cuisineInput} onChange={handleCuisineChange} />
          <textarea className="ingredients-input" placeholder="Ingredients" value={ingredientsInput} onChange={handleIngredientsChange} />
          <input className="image-input" type="file" accept="image/*" onChange={handleImageChange} />
          <button className="submit-btn" onClick={handleSubmit}>Create Recipe</button>
        </div>
      )}
    </div>
  );
};

export default CreateRecipe;
