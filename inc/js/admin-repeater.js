/**
 * Repeatable meta-box rows for the Ameer product editor.
 * Each repeater is a .ameer-rep-wrap with a .ameer-rep-rows list, a
 * <script.ameer-rep-tpl> template (using __i__ as the row index placeholder),
 * and a .ameer-rep-add button. Rows carry a .ameer-rep-remove button.
 */
(function () {
	'use strict';

	var seq = Date.now();

	function uid() {
		seq += 1;
		return 'n' + seq;
	}

	document.addEventListener('click', function (e) {
		var addBtn = e.target.closest('.ameer-rep-add');
		if (addBtn) {
			e.preventDefault();
			var wrap = addBtn.closest('.ameer-rep-wrap');
			if (!wrap) return;
			var tpl = wrap.querySelector('.ameer-rep-tpl');
			var rows = wrap.querySelector('.ameer-rep-rows');
			if (!tpl || !rows) return;
			var html = tpl.textContent.replace(/__i__/g, uid());
			var temp = document.createElement('div');
			temp.innerHTML = html.trim();
			var node = temp.firstElementChild;
			if (node) {
				rows.appendChild(node);
				var firstInput = node.querySelector('input, textarea');
				if (firstInput) firstInput.focus();
			}
			return;
		}

		var rmBtn = e.target.closest('.ameer-rep-remove');
		if (rmBtn) {
			e.preventDefault();
			var row = rmBtn.closest('.ameer-rep-row');
			if (row) row.remove();
		}
	});
})();
