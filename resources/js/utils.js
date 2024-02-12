export const urlToImageFile = async (url) => {
  if (url) {
    let result = await fetch(url);
    let blob = await result.blob()
    return new File([blob], 'image.jpg', blob)
  }
}