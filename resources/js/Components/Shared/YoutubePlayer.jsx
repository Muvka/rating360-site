import React, { useEffect, useState } from 'react';
import clsx from 'clsx';

const YoutubePlayer = ({
	videoId: videoIdProp = '',
	videoUrl: videoUrlProp = '',
	className = ''
}) => {
	const [videoId, setVideoId] = useState(videoIdProp);
	const [isActivated, setIsActivated] = useState(false);
	const videoUrl = `https://www.youtube.com/embed/${videoId}?rel=0&showinfo=0&autoplay=1`;

	const extractVideoId = url => {
		const patterns = [
			/(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([^&]+)/,
			/(?:https?:\/\/)?youtu\.be\/([^?&]+)/
		];

		for (let pattern of patterns) {
			const match = url.match(pattern);
			if (match && match[1]) {
				return match[1];
			}
		}

		return '';
	};

	useEffect(() => {
		if (!videoUrlProp) {
			return;
		}

		setVideoId(extractVideoId(videoUrlProp));
	}, [videoUrlProp]);

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
