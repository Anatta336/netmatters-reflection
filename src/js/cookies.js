export default function cookies() {
  const permissionName = 'acceptedCookies';

  /**
   * Stores a cookie indicating that the user has accepted the use of cookies on this site.
   * Cookie has a lifespan of 1 year.
   */
  function storePermissionCookie() {
    const secondsInAYear = 60 * 60 * 24 * 365;
    document.cookie = `${permissionName}=true; SameSite=Strict; max-age=${secondsInAYear}`;
  }


  /**
   * Checks if the user has already accepted the use of cookies for this site.
   * @returns {boolean} True if a permission cookie for this site has already been stored.
   */
  function checkForPermissionCookie() {
    // note this only checks that the cookie exists, not its value
    if (document.cookie.split(';').some((item) => item.trim().startsWith(`${permissionName}=`))) {
      return true;
    }
    return false;
  }

  return {
    storePermissionCookie,
    checkForPermissionCookie,
  };
}
