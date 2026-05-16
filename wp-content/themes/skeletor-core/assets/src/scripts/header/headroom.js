export function applyHeadroom(header) {
	import('headroom.js').then((module) => {
		const Headroom = module.default;

		const headroom = new Headroom(header, {
			onPin: () => {
				const body = document.body;
				body.classList.add('headroom-pinned');
				body.classList.remove('headroom-unpinned');
			},
			onUnpin: () => {
				const body = document.body;
				body.classList.add('headroom-unpinned');
				body.classList.remove('headroom-pinned');
			},
		});

		headroom.init();
	});
}
