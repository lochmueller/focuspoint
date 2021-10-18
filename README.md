# EXT:focuspoint

[![Latest Stable Version](https://poser.pugx.org/lochmueller/focuspoint/v/stable)](https://packagist.org/packages/lochmueller/focuspoint)
[![Total Downloads](https://poser.pugx.org/lochmueller/focuspoint/downloads)](https://packagist.org/packages/lochmueller/focuspoint)
[![License](https://poser.pugx.org/lochmueller/focuspoint/license)](https://packagist.org/packages/lochmueller/focuspoint)
[![TYPO3](https://img.shields.io/badge/TYPO3-10-orange.svg)](https://typo3.org/)
[![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/lochmueller/focuspoint.svg)](http://isitmaintained.com/project/lochmueller/focuspoint "Average time to resolve an issue")
[![Percentage of issues still open](http://isitmaintained.com/badge/open/lochmueller/focuspoint.svg)](http://isitmaintained.com/project/lochmueller/focuspoint "Percentage of issues still open")

## Installation

### Add TypoScript
Include in static template or add TypoScript includes to your extension or template.
```typo3_typoscript
@import 'EXT:focuspoint/Configuration/TypoScript/setup.txt'

# Setup must be after "page" TypoScript
@import 'EXT:focuspoint/Configuration/TypoScript/setup.txt'
```

### Fluid template (Example)
Edit your fluid template.
```html
<div style="height: 400px;">
    <fp:image image="{image}" width="1000c" height="400c" realCrop="false"/>
</div>
<div style="height: 400px;">
    <fp:image src="{image.uid}" treatIdAsReference="1" width="1000c" height="400c" realCrop="false"/>
</div>
```

### Custom CSS
Maybe you want to add additional css, for fluid example.
```css
.focuspoint {
  position: relative;
  height: 100%;
  overflow: hidden;
}
```

## Sponsors & supporter

Thank you for support and sponsoring the extension:

- Violetta Digital Craft GmbH, [www.violetta.ch](https://www.violetta.ch) - TYPO3 v10 and v11 support
- and [all contributors](https://github.com/lochmueller/focuspoint/graphs/contributors)!

![GitHub Contributors Image](https://contrib.rocks/image?repo=lochmueller/focuspoint)
