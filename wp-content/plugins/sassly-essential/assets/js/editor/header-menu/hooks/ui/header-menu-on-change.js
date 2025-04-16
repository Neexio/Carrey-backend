import { HeaderMenuOnAttachPreview } from './header-menu-on-attach-preview.js';

export class HeaderMenuOnChange extends HeaderMenuOnAttachPreview {
  getCommand() {
    return 'document/save/set-is-modified';
  }

  getId() {
    return 'custom_direction';
  }
}
