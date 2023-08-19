import React, { useState, useEffect } from 'react';
import './styles.css'

const UserProfileInfo = () => {
  const [name, setName] = useState('');
  const [totalLikes, setTotalLikes] = useState(0);
  const [totalFollowers, setTotalFollowers] = useState(0);
  const [newPostImageUrl, setNewPostImageUrl] = useState('');


  const fetchUserInfo = async () => {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        console.log('Token not found.');
        return;
      }

      const likesResponse = await fetch('http://127.0.0.1:8000/api/user/likes', {
        method: 'GET',
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      const followersResponse = await fetch('http://127.0.0.1:8000/api/user/followers', {
        method: 'GET',
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      const likesData = await likesResponse.json();
      const followersData = await followersResponse.json();

      setName(likesData.user_name);
      setTotalLikes(likesData.total_likes);
      setTotalFollowers(followersData.total_followers);

    } catch (error) {
      console.log('Error:', error);
    }
  };

  const handleAddPost = async () => {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        console.log('Token not found.');
        return;
      }

      const response = await fetch('http://127.0.0.1:8000/api/addpost', {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ image_url: newPostImageUrl }),
      });

      const data = await response.json();
      console.log('Add post response:', data.massage);
      setNewPostImageUrl("")
      fetchUserInfo()
      ;
    } catch (error) {
      console.log('Error:', error);
    }
  };

  useEffect(() => {
    fetchUserInfo();

  }, []); 

  return (
    <div className="user-profile-info">
      <div className='user-status'>
        <p>User Name: {name}</p>
        <p>Total Likes: {totalLikes}</p>
        <p>Total Followers: {totalFollowers}</p>
      </div>
      <div className='user-input'>
        <input type="text" placeholder="Image URL" value={newPostImageUrl} onChange={(e) => setNewPostImageUrl(e.target.value)} />
        <button onClick={handleAddPost}>Add Post</button>
      </div>

    </div>
  );
};


export default UserProfileInfo;
