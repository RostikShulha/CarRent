const price = parseFloat(priceString.replace(','))

// Стан авто
$(document).ready(function () {
	const $buttonStan = $('#list__button-stan')
	const stanId = $buttonStan.data('stan-id')

	if (stanId === 1) {
		$buttonStan.addClass('list__stan-orend')
	} else if (stanId === 3) {
		$buttonStan.wrap('list__stan-hotov')
		$buttonStan.addClass('list__stan-ne-orend')
	} else if (stanId === 2) {
		$buttonStan.addClass('list__stan-remont')
	}
})
// Стан авто

// Header
const headerEl = document.getElementById('header')

window.addEventListener('scroll', function () {
	const scrollPos = window.scrollY

	if (scrollPos > 60) {
		headerEl.classList.add('header_mini')
	} else {
		headerEl.classList.remove('header_mini')
	}
})
// Header

document.addEventListener('DOMContentLoaded', function () {
	const startDateInput = document.querySelector('#start-date')
	const endDateInput = document.querySelector('#end-date')

	if (startDateInput) {
		let today = new Date()
		let year = today.getFullYear()
		let month = (today.getMonth() + 1).toString().padStart(2, '0')
		let day = today.getDate().toString().padStart(2, '0')
		let todayStr = `${year}-${month}-${day}`

		startDateInput.value = todayStr
		endDateInput.value = todayStr
		startDateInput.setAttribute('min', todayStr)
		endDateInput.setAttribute('min', todayStr)

		startDateInput.addEventListener('change', function () {
			let selectedDate = startDateInput.value
			endDateInput.setAttribute('min', selectedDate)

			if (endDateInput.value < selectedDate) {
				endDateInput.value = selectedDate
			}
			if (startDateInput.value > selectedDate) {
				startDateInput.value = selectedDate
			}

			endDateInput.addEventListener('change', function () {
				let selectedDate = endDateInput.value
				startDateInput.setAttribute('max', selectedDate)
			})
		})
	} else {
		console.error('Element with ID "start-date" not found.')
	}

	document.querySelector('#date-submit').onclick = function () {
		let dateStart = new Date(document.querySelector('#start-date').value)
		let dateEnd = new Date(document.querySelector('#end-date').value)
		if (dateStart <= dateEnd) {
			numberDays = (dateEnd - dateStart) / (24 * 3600 * 1000)
			totalPrice = price * numberDays
			if (totalPrice <= 0) {
			}
		}
	}
})

// кнопка відправлення дати
// Отримуємо посилання на елемент div
var divElement = document.querySelector('.list__specs_button_under')

// Додаємо обробник події click до елемента div
divElement.addEventListener('click', function () {
	// Знаходимо кнопку за її ідентифікатором
	var buttonElement = document.getElementById('date-submit')
	// Спрацьовуємо клік на кнопці
	buttonElement.click()
})

// PopWindow
document.getElementById('date-submit').addEventListener('click', function () {
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

/// PopWindow розрахунок днів оренди
document.getElementById('date-submit').addEventListener('click', function () {
	const startDate = document.getElementById('start-date').value
	const endDate = document.getElementById('end-date').value

	const startDateTimestamp = new Date(startDate).getTime()
	const endDateTimestamp = new Date(endDate).getTime()

	if (
		startDateTimestamp &&
		endDateTimestamp &&
		endDateTimestamp >= startDateTimestamp
	) {
		const rentalDays =
			Math.floor(
				(endDateTimestamp - startDateTimestamp) / (1000 * 60 * 60 * 24)
			) + 1
		const daysWord = formatDaysWord(rentalDays)
		const price = parseFloat(priceString.replace(/,/g, ''))
		const totalPrice = rentalDays * price
		const startDateFormatted = formatDate(new Date(startDate))
		const endDateFormatted = formatDate(new Date(endDate))

		document.getElementById(
			'rental-date-start'
		).innerHTML = `<h4>Дата початку оренди: ${startDateFormatted}</h4>`
		document.getElementById(
			'rental-date-end'
		).innerHTML = `<h4>Дата кінця оренди: ${endDateFormatted}</h4>`
		document.getElementById(
			'rental-price'
		).innerHTML = `<h4>Вартість оренди на ${rentalDays} ${daysWord}:&nbsp;&nbsp;&nbsp;&nbsp;${totalPrice}$</h4>`

		// Заповнення прихованих полів для форми
		document.getElementById('hidden-start-date').value = startDate
		document.getElementById('hidden-end-date').value = endDate
		document.getElementById('hidden-rental-days').value = rentalDays
		document.getElementById('hidden-total-price').value = totalPrice
	}
})

function formatDaysWord(days) {
	if (
		days === 0 ||
		(days >= 5 && days <= 20) ||
		(days % 100 >= 10 && days % 100 <= 20) ||
		(days % 10 >= 5 && days % 10 <= 9)
	) {
		return 'днів'
	} else if (days === 1 || (days % 10 === 1 && days !== 11)) {
		return 'день'
	} else {
		return 'дні'
	}
}

function formatDate(date) {
	const day = String(date.getDate()).padStart(2, '0')
	const month = String(date.getMonth() + 1).padStart(2, '0')
	const year = date.getFullYear()
	return `${day}.${month}.${year}`
}
