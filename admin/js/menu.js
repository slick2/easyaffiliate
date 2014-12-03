var at_timeout = 50;

// ----- Show Aux -----

function at_show_aux(parent, child)
{
  var p = document.getElementById(parent);
  var c = document.getElementById(child);

  p.className = "active";

  var top  = (c["at_position"] == "y") ? p.offsetHeight+2 : 0;
  var left = (c["at_position"] == "x") ? p.offsetWidth +2 : 0;

  for (; p; p = p.offsetParent)
  {
    if (p.style.position != 'absolute')
    {
      left += p.offsetLeft;
      top  += p.offsetTop;
    }
  }

  c.style.position   = "absolute";
  c.style.top        = top +'px';
  c.style.left       = left+'px';
  c.style.visibility = "visible";
}

// ----- Hide Aux -----

function at_hide_aux(parent, child)
{
  document.getElementById(parent).className        = "parent";
  document.getElementById(child ).style.visibility = "hidden";
}

// ----- Show -----

function at_show()
{
  var p = document.getElementById(this["at_parent"]);
  var c = document.getElementById(this["at_child" ]);

  at_show_aux(p.id, c.id);

  clearTimeout(c["at_timeout"]);
}

// ----- Hide -----

function at_hide()
{
  var c = document.getElementById(this["at_child"]);

  c["at_timeout"] = setTimeout("at_hide_aux('"+this["at_parent"]+"', '"+this["at_child" ]+"')", at_timeout);
}

// ----- Attach -----

function at_attach(parent, child, position)
{
  p = document.getElementById(parent);
  c = document.getElementById(child );

  p["at_child"]    = c.id;
  c["at_child"]    = c.id;
  p["at_parent"]   = p.id;
  c["at_parent"]   = p.id;
  c["at_position"] = position;

  p.onmouseover = at_show;
  p.onmouseout  = at_hide;
  c.onmouseover = at_show;
  c.onmouseout  = at_hide;
}

// ----- DropDown Menu ---------------------------------------------------------

// ----- Build Aux -----

function dhtmlmenu_build_aux(parent, child, position)
{
  document.getElementById(parent).className = "parent";

  document.write('<div class="vert_menu" id="'+parent+'_child">');

  var n = 0;
  for (var i in child)
  {
    if (i == '-')
    {
      document.getElementById(parent).href = child[i];
      continue;
    }

    if (typeof child[i] == "object")
    {
      document.write('<a class="parent" id="'+parent+'_'+n+'">'+i+'</a>');
      dhtmlmenu_build_aux(parent+'_'+n, child[i], "x");
    }
    else document.write('<a id="'+parent+'_'+n+'" href="'+child[i]+'">'+i+'</a>');
    n++;
  }

  document.write('</div>');

  at_attach(parent, parent+"_child", position);
}

// ----- Build -----

function dhtmlmenu_build(menu)
{
  for (var i in menu) dhtmlmenu_build_aux(i, menu[i], "y");
}
