import * as hooks from './hooks/ui/index.js';

export default class Component extends $e.modules.ComponentBase {
  getNamespace() {
    return 'wcf--header-menu';
  }

  defaultHooks() {
    return this.importHooks( hooks );
  }

  ControlChange() {
    // Clone current document elements.
    const html = elementor.documents.getCurrent().$element.clone(); 
   
  }
}
