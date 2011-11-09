<html><head></head><body>

<#if document?exists>
   <h3>Document Version History for: ${document.name}</h3>
   <table cellspacing=4 border=1>
      <tr align=left><th>Version</th><th>Name</th><th>Description</th><th>Created Date</th><th>Creator</th></tr>
      <#list document.versionHistory as record>
         <tr>
            <td><a href="/alfresco${record.url}" target="new">${record.versionLabel}</a></td>
            <td><a href="/alfresco${record.url}" target="new">${record.name}</a></td>
            <td><#if record.description?exists>${record.description}</#if></td>
            <td>${record.createdDate?datetime}</td>
            <td>${record.creator}</td>
         </tr>
      </#list>
   </table>
<#else>
   No document found!
</#if>

</body></html>

