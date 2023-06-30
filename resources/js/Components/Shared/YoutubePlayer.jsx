import React, { useState } from 'react';
import clsx from 'clsx';

const YoutubePlayer = ({ videoId = '', className = '' }) => {
	const [isActivated, setIsActivated] = useState(false);
	const videoUrl = `https://www.youtube.com/embed/${videoId}?rel=0&showinfo=0&autoplay=1`;

	if (!videoId) {
		return false;
	}

	return (
		<div
			className={clsx(
				'youtube-player',
				{
					'youtube-player--activated': isActivated
				},
				className
			)}
			onClick={() => setIsActivated(true)}
		>
			<picture>
				<source
					srcSet={`https://i.ytimg.com/vi_webp/${videoId}/maxresdefault.webp`}
					type='image/webp'
				/>
				<img
					className='youtube-player__media'
					src={`https://i.ytimg.com/vi/${videoId}/maxresdefault.jpg`}
					alt=''
				/>
			</picture>
			{isActivated ? (
				<iframe
					src={videoUrl}
					allowFullScreen
					allow='autoplay'
					className='youtube-player__media'
				/>
			) : (
				<button
					className='youtube-player__button'
					aria-label='Запустить видео'
				/>
			)}
		</div>
	);
};

export default YoutubePlayer;
