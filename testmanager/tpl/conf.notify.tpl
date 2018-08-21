{% include localPath(0) ~ "conf.navi.tpl" %}
<style>
  .conf-notify {
    margin: 10px 0;
    padding: 20px 5px 20px 25px;
    font-weight: bold; 
    border: 1px solid red; 
    cursor: pointer; 
    background: #fff; 
    background-position: 8px center;
  }
</style>
<div class="conf-notify">
  {{ content }}
</div>