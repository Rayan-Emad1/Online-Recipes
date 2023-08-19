import React, { useState } from 'react';
import './styles.css';

const Post = ({ post, getPosts }) => {
  const token = localStorage.getItem('token');
  const [isLiked, setIsLiked] = useState('');

  const handleLikeToggle = async (postId) => {
    try {
      if (!token) {
        console.log('Token not found.');
        return;
      }

      const response = await fetch('http://127.0.0.1:8000/api/like', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ post_id: postId }),
      });

      const data = await response.json();
      console.log('Like post response:', data);
      setIsLiked(data.message);
      console.log(isLiked)
      getPosts();

    } catch (error) {
      console.log('Error:', error);
    }
  };

  return (
    <div className="post-card">
      <img className='post-image' src={post.image_url} alt={`Post by ${post.user_name}`} />
      <h3>{post.user_name}</h3>
      <div className='like-container'>
        <span>Likes: {post.likes}</span>
        <button
          className={`like-button-post ${isLiked === 'Post unliked.' ? 'unlike' : ''}`}
          onClick={() => handleLikeToggle(post.id)}
        >
          {isLiked === 'Post unliked.' ? 'Unlike' : 'Like'}
        </button>
      </div>
    </div>
  );
};

export default Post;
