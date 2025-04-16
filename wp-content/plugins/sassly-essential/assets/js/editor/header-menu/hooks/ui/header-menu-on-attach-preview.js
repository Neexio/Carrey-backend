export class HeaderMenuOnAttachPreview extends $e.modules.hookUI.After {
  getCommand() {
    return 'editor/documents/attach-preview';
  }

  getId() {
    return 'wcf-header-menu-on-attach-preview';
  }

  /*
  getConditions( args ) {
    // Apply only if current page settings has some setting tab.
    return Object.keys( $e.components.get( 'panel/page-settings' ).getTabs() ).includes( 'new-tab' );
  }
  */

  apply() {
    
    // Give some milliseconds to ensure the UI has been updated.
    setTimeout( () => $e.components.get( 'wcf--header-menu' ).ControlChange(), 100 );
  }
}
