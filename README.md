# EXT:focuspoint

[![Build Status](https://travis-ci.org/lochmueller/focuspoint.svg?branch=master)](https://travis-ci.org/lochmueller/focuspoint)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lochmueller/focuspoint/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lochmueller/focuspoint/?branch=master)

## Installation

### Add TypoScript
Include in static template or add TypoScript includes to your extension or template.
```typo3_typoscript
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:focuspoint/Configuration/TypoScript/setup.txt">

# Setup must be after "page" TypoScript
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:focuspoint/Configuration/TypoScript/setup.txt">
```

### Fluid template (Example)
Edit your fluid template.
```html
{namespace fp=HDNET\Focuspoint\ViewHelpers}
<div style="height: 400px;">
    <fp:image image="{image}" width="1000c" height="400c" realCrop="false"/>
</div>
<div style="height: 400px;">
    <fp:image src="{image.uid}" treatIdAsReference="1" width="1000c" height="400c" realCrop="false"/>
</div>
```

### Custom CSS
Maybe you want to add additional css, for fluid example
```css
.focuspoint {
  position: relative;
  height: 100%;
  overflow: hidden;
}
```
