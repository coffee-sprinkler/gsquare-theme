jQuery(document).ready(function ($) {
  function updateProgressValue() {
    $.ajax({
      type: 'GET',
      url: ajax_object.ajax_url,
      data: {
        action: 'get_dynamic_donation_content',
      },
      success: function (response) {
        const numericValue = parseNumberFromString(response)

        const progressBar = $('#progress-bar')
        const totalVal = parseFloat(progressBar.attr('data-progress-total'))
        const percentage = (numericValue / totalVal) * 100

        progressBar.css('--progress-width', percentage + '%')
      },
      error: function (error) {
        console.error(error)
      },
    })
  }

  function parseNumberFromString(str) {
    const numericStr = str.replace(/[^\d.]/g, '')

    const numberValue = parseFloat(numericStr)

    if (str.toLowerCase().includes('million')) {
      return numberValue * 1000000
    }

    return numberValue
  }

  function toastifySuccess(response) {
    Toastify({
      text: response,
      duration: 3000,
      position: 'right',
      style: {
        background: '#5d8c92',
      },
    }).showToast()
  }

  function formSubmitSuccess(response) {
    toastifySuccess(response)

    $('#donation-form')[0].reset()

    $('#donation-form').find(':submit').prop('disabled', false)

    $('html, body').animate({ scrollTop: 0 }, 'smooth')

    refreshDynamicContent(response)

    updateProgressValue()
  }

  function disableSubmitButton() {
    $('#donation-form').find(':submit').prop('disabled', true)
  }

  function setDonationInput() {
    const donationInput = $('#donate-amount')

    donationInput.on('input', function (e) {
      let inputValue = $(this).val()

      let numericValue = inputValue.replace(/[^0-9.]/g, '')

      $(this).val(numericValue)
    })

    const donatedInput = $('#donated-amount')

    donatedInput.on('input', function (e) {
      let inputValue = $(this).val()

      let numericValue = inputValue.replace(/[^0-9.]/g, '')

      $(this).val(numericValue)
    })
  }

  function validateForm() {
    // Validate First Name
    const firstNameInput = document.getElementById('firstName')
    const firstNameValue = firstNameInput.value.trim()
    const firstNamePattern = /^[A-Za-z]+$/

    if (!firstNamePattern.test(firstNameValue)) {
      toastifyError(
        'Please enter a valid first name using only alphabetical characters.'
      )
      return false
    }

    // Validate Last Name
    const lastNameInput = document.getElementById('lastName')
    const lastNameValue = lastNameInput.value.trim()
    const lastNamePattern = /^[A-Za-z]+$/

    if (!lastNamePattern.test(lastNameValue)) {
      toastifyError(
        'Please enter a valid last name using only alphabetical characters.'
      )
      return false
    }

    // Validate Email
    const emailInput = document.getElementById('email')
    const emailValue = emailInput.value.trim()
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

    if (!emailPattern.test(emailValue)) {
      toastifyError('Please enter a valid email address.')
      return false
    }

    // Validate Phone Number for Philippine telco (starts with "09")
    const phoneInput = document.getElementById('phone')
    const phoneValue = phoneInput.value.trim()
    const phonePattern = /^09\d{9}$/ // Assumes a 11-digit phone number starting with "09"

    if (!phonePattern.test(phoneValue)) {
      toastifyError(
        'Please enter a valid Philippine phone number starting with "09".'
      )
      return false
    }

    return true
  }

  function toastifyError(message) {
    Toastify({
      text: message,
      duration: 3000,
      position: 'right',
      style: {
        background: '#d9534f',
      },
    }).showToast()
  }

  function submitForm() {
    $('#donation-form').submit(function (e) {
      e.preventDefault()

      // Validate the form
      if (!validateForm()) return

      disableSubmitButton()

      const formData = $(this).serialize()

      $.ajax({
        type: 'POST',
        url: ajax_object.ajax_url,
        data: {
          action: 'process_donation_form',
          form_data: formData,
        },
        success: function (response) {
          formSubmitSuccess(response)
        },
        error: function (error) {
          console.error(error)

          $('#donation-form').find(':submit').prop('disabled', false)
        },
      })
    })
  }

  function refreshDynamicContent(response) {
    $.ajax({
      type: 'GET',
      url: ajax_object.ajax_url,
      data: {
        action: 'get_dynamic_donation_content',
      },
      success: function (response) {
        if (!response) return
        $('#dynamic-donation-content').html('$' + response)

        initDonationAnimation(0, response)
      },
      error: function (error) {
        console.error(error)
      },
    })
  }

  function formatNumberCurrency(value) {
    const numericValue = parseFloat(value.replace(/[^\d.]/g, ''))

    if (!isNaN(numericValue)) {
      const formattedValue = numericValue.toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD',
      })

      return formattedValue
    }

    return value
  }

  function initDonationAnimation(response) {
    const dynamicContent = $('#dynamic-donation-content')
    const currentAmount = parseFloat(
      dynamicContent.text().replace('$', '').replace(',', '')
    )

    const responseString = String(response)

    // Check if responseString is a valid number
    if (!isNaN(parseFloat(responseString)) && isFinite(responseString)) {
      const newAmount =
        currentAmount + parseInt(responseString.replace(',', ''))

      dynamicContent.stop().animate(
        { value: newAmount },
        {
          duration: 1500,
          easing: 'swing',
          step: function (now) {
            dynamicContent.text(formatNumberCurrency(now.toFixed(2)))
          },
        }
      )
    }
  }

  $('#donate-amount').on('focusout', function (e) {
    const formattedValue = formatNumberCurrency(e.target.value)
    $('#donate-amount').val(formattedValue.replace('$', ''))
    $('#donated-amount').val(formattedValue)
  })

  $('#donated-amount').on('focusout', function (e) {
    const formattedValue = formatNumberCurrency(e.target.value)
    $('#donate-amount').val(formattedValue.replace('$', ''))
    $('#donated-amount').val(formattedValue)
  })

  setDonationInput()

  submitForm()
  updateProgressValue()
  refreshDynamicContent()
})
