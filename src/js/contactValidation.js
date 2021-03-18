/**
 * 
 * @param {HTMLFormElement} form
 * @param {HTMLInputElement} nameInput 
 * @param {HTMLElement} nameMissingError
 * @param {HTMLInputElement} emailInput 
 * @param {HTMLInputElement} phoneInput 
 * @param {HTMLElement} contactMissingError
 * @param {HTMLInputElement} messageInput 
 * @param {HTMLElement} messageMissingError
 * @param {HTMLInputElement} submitButton 
 */
export default function contactValidation(
    form,
    nameInput, nameMissingError,
    emailInput, phoneInput, contactMissingError,
    messageInput, messageMissingError,
    submitButton,
  ) {
  
  if (!form) {
    throw new TypeError('form must be non-null');
  }
  if (!nameInput) {
    throw new TypeError('nameInput must be non-null');
  }
  if (!nameMissingError) {
    throw new TypeError('nameMissingError must be non-null');
  }
  if (!emailInput) {
    throw new TypeError('emailInput must be non-null');
  }
  if (!phoneInput) {
    throw new TypeError('phoneInput must be non-null');
  }
  if (!contactMissingError) {
    throw new TypeError('contactMissingError must be non-null');
  }
  if (!messageInput) {
    throw new TypeError('messageInput must be non-null');
  }
  if (!messageMissingError) {
    throw new TypeError('messageMissingError must be non-null');
  }
  if (!submitButton) {
    throw new TypeError('submitButton must be non-null');
  }

  // intercept attempt to submit form, only allowing it through
  // if basic validation passes. Show any errors.
  submitButton.addEventListener('click', (event) => {
    event.preventDefault();

    const isValid = checkAllValidation();
      
    if (isValid) {
      form.submit();
    }
  });

  // allow entering data to remove errors, but don't trigger
  // new errors while typing to avoid an overly aggressive UX
  nameInput.addEventListener('input', () => {
    if (!isFieldEmpty(nameInput)) {
      hideError(nameMissingError);
    }
  });

  emailInput.addEventListener('input', () => {
    if (!isFieldEmpty(emailInput)) {
      hideError(contactMissingError);
    }
  });
  phoneInput.addEventListener('input', () => {
    if (!isFieldEmpty(phoneInput)) {
      hideError(contactMissingError);
    }
  });

  messageInput.addEventListener('input', () => {
    if (!isFieldEmpty(messageInput)) {
      hideError(messageMissingError);
    }
  });

  /**
   * Checks all validation, displaying errors for any that fail.
   * @returns {boolean} True if all validation passes.
   */
  function checkAllValidation() {
    let isValid = true;
    if (isFieldEmpty(nameInput)) {
      showError(nameMissingError);
      isValid = false;
    } else {
      hideError(nameMissingError);
    }
    if (isFieldEmpty(messageInput)) {
      showError(messageMissingError);
      isValid = false;
    } else {
      hideError(messageMissingError);
    }
    if (isFieldEmpty(emailInput) && isFieldEmpty(phoneInput)) {
      showError(contactMissingError);
      isValid = false;
    } else {
      hideError(contactMissingError);
    }
    return isValid;
  }

  function showError(errorElement) {
    errorElement.classList.add('show');
  }

  function hideError(errorElement) {
    errorElement.classList.remove('show');
  }

  /**
   * Check if a given HTML input field has any content.
   * @param {HTMLInputElement} field Input element to check contents of.
   * @returns {boolean} True if field has no content, otherwise false.
   */
  function isFieldEmpty(field) {
    return field.value == null || field.value == '';
  }
}