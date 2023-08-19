import React, { useState, useEffect } from 'react';
import Post from '../../Components/Post';
import './styles.css';

const PostContainer = ({ url }) => {
  const [posts, setPosts] = useState([]);

  const getPosts = async () => {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        console.log('Token not found.');
        return;
      }

      const response = await fetch(`http://127.0.0.1:8000/api${url}`, {
        method: 'GET',
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      const data = await response.json();
      setPosts(data.posts); 

    } catch (error) {
      console.log('Error:', error);
    }
  };

  const likePost = async (postId) => {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        console.log('Token not found.');
        return;
      }

      const response = await fetch('http://127.0.0.1:8000/api/like', {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ post_id: postId }),
      });

      const data = await response.json();
      console.log('Like post response:', data);
      getPosts();

    } catch (error) {
      console.log('Error:', error);
    }
  };

  useEffect(() => {
    getPosts();
  }, [url]);

  return (
    <div className="content">
      <div className="posts-container">
        {posts.map(post => (
          <Post key={post.id} post={post} likePost={likePost} getPosts={getPosts} />
        ))}
      </div>
    </div>
  );
};

export default PostContainer;
