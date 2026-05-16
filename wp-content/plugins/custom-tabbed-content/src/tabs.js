export const Tabs = ({ tabs = [], name = '' }) =>
	<div role="tablist" className="tabbed-content__tabs" aria-label={name}>
		{tabs.map((T, n) =>
			<button
				role="tab"
				aria-selected="false"
				className="tabbed-content__tab"
				key={`tab-${n}`}
			>
				{T}
			</button>
		)}
	</div>

