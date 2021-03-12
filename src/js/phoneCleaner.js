/**
 * 
 * @param {HTMLInputElement} inputElement The input element to keep clean.
 */
export default function phoneCleaner(inputElement) {
  inputElement.addEventListener('input', () => {
    inputElement.value = cleanPhoneNumber(inputElement.value);
  })

  function cleanPhoneNumber(original) {
    // the "ig" marks a regex global so it can be used in replaceAll
    // matches anything except: digits, +, (, ), and spaces
    // "(+44) 01235 67890" is valid and doesn't need any cleaning
    const invalid = /[^0-9/+() ]/ig;
    const trimmed = original.replaceAll(invalid, '');
  
    // matches a series of 2 or more spaces, then replace with a single space.
    const longGap = /( {2})+/ig;
    return trimmed.replaceAll(longGap, ' ');
  }

  return {};
}
