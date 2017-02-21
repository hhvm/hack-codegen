This provides guidance on how to contribute various content to `hack-codegen`.

## Basic Structure

Most content is written in markdown. You name the file `something.md`, then have a header that looks like this:

```
---
docid: getting-started
title: Getting started with ProjectName
layout: docs
permalink: /docs/getting-started.html
---
```

Customize these values for each document, blog post, etc.

> The filename of the `.md` file doesn't actually matter; what is important is the `docid` being unique and the `permalink` correct and unique too).

## Landing page

Modify `index.md` with your new or updated content.

If you want a `GridBlock` as part of your content, you can do so directly with HTML:

```
<div class="gridBlock">
  <div class="blockElement twoByGridBlock alignLeft">
    <div class="blockContent">
      <h3>Your Features</h3>
      <ul>
        <li>The <a href="http://example.org/">Example</a></li>
        <li><a href="http://example.com">Another Example</a></li>
      </ul>
    </div>
  </div>

  <div class="blockElement twoByGridBlock alignLeft">
    <div class="blockContent">
      <h3>More information</h3>
      <p>
         Stuff here
      </p>
    </div>
  </div>
</div>
```

or with a combination of changing `./_data/features.yml` and adding some Liquid to `index.md`, such as:

```
{% include content/gridblocks.html data_source=site.data.features imagealign="bottom"%}
```

## Docs

To modify docs, edit the appropriate markdown file in `./_docs/`.

To add docs to the site....

1. Add your markdown file to the `./_docs/` folder. See `./doc-type-examples/docs-hello-world.md` for an example of the YAML header format. **If the `./_docs/` directory does not exist, create it**.
  - You can use folders in the `./_docs/` directory to organize your content if you want.
1. Update `_data/nav_docs.yml` to add your new document to the navigation bar. Use the `docid` you put in your doc markdown in as the `id` in the `_data/nav_docs.yml` file.
1. [Run the site locally](./README.md) to test your changes. It will be at `http://127.0.0.1/docs/your-new-doc-permalink.html`
1. Push your changes to GitHub.

## Header Bar

To modify the header bar, change `./_data/nav.yml`.

## Top Level Page

To modify a top-level page, edit the appropriate markdown file in `./top-level/`

If you want a top-level page (e.g., http://your-site.com/top-level.html) -- not in `/blog/` or `/docs/`....

1. Create a markdown file in the root `./top-level/`. See `./doc-type-examples/top-level-example.md` for more information.
1. If you want a visible link to that file, update `_data/nav.yml` to add a link to your new top-level document in the header bar.

   > This is not necessary if you just want to have a page that is linked to from another page, but not exposed as direct link to the user.

1. [Run the site locally](./README.md) to test your changes. It will be at `http://127.0.0.1/your-top-level-page-permalink.html`
1. Push your changes to GitHub.

## Code Blocks

This template uses [rouge syntax highlighting](https://github.com/jneen/rouge) out of the box. We have [custom CSS](https://github.com/facebook/Site-Templates/blob/master/product-and-feature-docs/first-common-fb-template/_sass/_syntax-highlighting.scss) for when those blocks are rendered. Rouge has plenty of [supported languages](https://github.com/jneen/rouge/wiki/List-of-supported-languages-and-lexers). 

## Other Changes

- CSS: `./css/main.css` or `./_sass/*.scss`.
- Images: `./static/images/[docs | posts]/....`
- Main Blog post HTML: `./_includes/post.html`
- Main Docs HTML: `./_includes/doc.html`
