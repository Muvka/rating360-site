import React, { useId } from 'react';
import clsx from 'clsx';
import { usePage } from '@inertiajs/react';

import YoutubePlayer from '../Shared/YoutubePlayer.jsx';

const RatingInstruction = ({ className = '' }) => {
	const instruction = usePage().props?.instruction;
	const titleId = useId();

	if (!instruction) {
		return false;
	}

	return (
		<section
			className={clsx('rating-instruction', className)}
			aria-labelledby={titleId}
		>
			<h2 id={titleId} className='visually-hidden'>
				Инструкция
			</h2>
			{Boolean(instruction.text) && (
				<div
					className='text-content'
					dangerouslySetInnerHTML={{ __html: instruction.text }}
				/>
			)}
			{Boolean(instruction.video) && (
				<div className='rating-instruction__video-container'>
					<h3 className='title title--tiny rating-instruction__title'>
						Видео инструкция
					</h3>
					<YoutubePlayer
						videoUrl={instruction.video}
						className='rating-instruction__player'
					/>
				</div>
			)}
		</section>
	);
};

export default RatingInstruction;
