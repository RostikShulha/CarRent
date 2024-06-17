// PopWindow
document
	.getElementById('open-modal-btn')
	.addEventListener('click', function () {
		document.getElementById('my-modal').classList.add('open')
	})

document
	.getElementById('close-my-modal-btn')
	.addEventListener('pointerdown', function (event) {
		if (event.pointerType === 'mouse') {
			document.getElementById('my-modal').classList.remove('open')
		}
	})

window.addEventListener('keydown', e => {
	if (e.key === 'Escape') {
		document.getElementById('my-modal').classList.remove('open')
	}
})

document
	.querySelector('#my-modal .modal__box')
	.addEventListener('click', event => {
		event._isClickWithInModal = true
	})

document.getElementById('my-modal').addEventListener('click', event => {
	if (event._isClickWithInModal) return
	event.currentTarget.classList.remove('open')
})
// PopWindow

// --------------header code---------------------------
const headerEl = document.getElementById('header')

window.addEventListener('scroll', function () {
	const scrollPos = window.scrollY

	if (scrollPos > 60) {
		headerEl.classList.add('header_mini')
	} else {
		headerEl.classList.remove('header_mini')
	}
})
// --------------header code---------------------------

// Ajax - з'єднання до БД для вибору моделі залежно від марки
$(document).ready(function () {
	$('#marka').on('change', function () {
		var markaID = $(this).val()
		if (markaID) {
			$.ajax({
				type: 'POST',
				url: '', // Відправляємо запит на той самий файл
				data: { marka_id: markaID },
				success: function (response) {
					$('#model').html(response)
				},
			})
		} else {
			$('#model').html('<option value="">Виберіть спочатку марку</option>')
		}
	})
})
// Ajax - з'єднання до БД для вибору моделі залежно від марки
