function passwordStrength( passwd ) {
  var charsetSize = 0;
  if ( /[a-z]/.test( passwd ))
    charsetSize += 26;
  if ( /[A-Z]/.test( passwd ))
    charsetSize += 26;
  if ( /[0-9]/.test( passwd ))
    charsetSize += 10;
  if ( /[!@#$%^&*\(\)<>\/?.,'"\-_=+\[\]\{\}\\\|~`:;]/.test( passwd ))
    charsetSize += 28;
  if ( /δειλώόϊνσφαίποψζ©®bρµηΏ΅£½Ύχ/.test( passwd ))
    charsetSize += 28;
  if ( / /.test( passwd ))
    charsetSize++;
  var entropy = passwordEntropy( charsetSize , passwd.length );
  var text = strengthText( entropy );
  return { 
    'entropy': entropy,
    'text': text,
    'color': strengthColor( text )
  }
}
function passwordEntropy( charsetSize, length ) {
  if ( !charsetSize || !length )
    return 0;
  return Math.log( charsetSize ) / Math.LN2 * length;
}
function strengthText( entropy ) {
  if ( entropy < 20 ) 
    return 'Very Weak';
  if ( entropy < 35 ) 
    return 'Weak';
  if ( entropy < 50 ) 
    return 'Average';
  if ( entropy < 65 ) 
    return 'Good';
  if ( entropy < 75 ) 
    return 'Great';
  return 'Excellent';
}
function strengthColor( strength ) {
  switch( strength ) {
    case 'Very Weak':
      return '#f00';
    case 'Weak':
      return '#f80';
    case 'Average':
      return '#099';
    case 'Good':
      return '#893';
    case 'Great':
      return '#693';
    default:
      return '#090';
  }
}
function testPassword( value ) {
  var element = jQuery( '#passwordStrength' );
  var strength = passwordStrength( value );
  element.text( strength.text );
  element.css( 'color', strength.color );
}
